<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\CertificateBranding;
use App\Models\Course;
use App\Models\LessonCompletion;
use App\Support\EnsuresStudentEnrollment;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CourseCertificateController extends Controller
{
    use EnsuresStudentEnrollment;

    public function store(Request $request, Course $course): RedirectResponse
    {
        $user = $request->user();
        $enrollment = $this->ensureEnrollment($user, $course);

        $course->loadMissing(['modules.lessons', 'finalTest']);

        [$eligible, $message] = $this->checkEligibility($user->id, $course->id);

        if (! $eligible) {
            return back()->withErrors(['certificate' => $message]);
        }

        $certificate = Certificate::firstOrNew([
            'course_id' => $course->id,
            'user_id' => $user->id,
        ]);

        $branding = $this->resolveBranding($course);
        $issuedAt = now();
        $displayName = $user->preferredName();

        if (! $certificate->exists) {
            $certificate->number = 'EDUX-' . strtoupper(Str::random(8));
            $certificate->public_token = (string) Str::uuid();
            $publicUrl = route('certificates.verify', $certificate->public_token);

            $certificate->front_content = view('learning.certificates.templates.front', [
                'course' => $course,
                'branding' => $branding,
                'displayName' => $displayName,
                'issuedAt' => $issuedAt,
                'publicUrl' => $publicUrl,
            ])->render();

            $certificate->back_content = view('learning.certificates.templates.back', [
                'course' => $course,
                'branding' => $branding,
            ])->render();

            $certificate->issued_at = $issuedAt;
            $certificate->save();
        } elseif (! $certificate->public_token) {
            $certificate->public_token = (string) Str::uuid();
            $certificate->save();
        }

        $enrollment->forceFill(['completed_at' => now(), 'progress_percent' => 100])->save();

        return redirect()->route('learning.courses.certificate.show', [$course, $certificate])
            ->with('status', 'Certificado emitido com sucesso!');
    }

    public function show(Request $request, Course $course, Certificate $certificate): View
    {
        $user = $request->user();
        $this->ensureEnrollment($user, $course);

        abort_if($certificate->course_id !== $course->id || $certificate->user_id !== $user->id, 403);

        return view('learning.certificates.show', [
            'course' => $course,
            'certificate' => $certificate,
            'publicUrl' => route('certificates.verify', $certificate->public_token),
        ]);
    }

    public function download(Request $request, Course $course, Certificate $certificate)
    {
        $user = $request->user();
        $this->ensureEnrollment($user, $course);

        abort_if($certificate->course_id !== $course->id || $certificate->user_id !== $user->id, 403);

        $pdf = $this->makePdf($certificate, $course);

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="certificado-'.$course->slug.'.pdf"',
        ]);
    }

    /**
     * @return array{0: bool, 1: string|null}
     */
    private function checkEligibility(int $userId, int $courseId): array
    {
        $course = Course::with(['lessons', 'finalTest'])->findOrFail($courseId);

        $lessonIds = $course->lessons->pluck('id');
        $totalLessons = $lessonIds->count();
        $completedLessons = LessonCompletion::query()
            ->whereIn('lesson_id', $lessonIds)
            ->where('user_id', $userId)
            ->count();

        if ($totalLessons === 0) {
            return [false, 'Este curso ainda não possui aulas cadastradas.'];
        }

        if ($completedLessons < $totalLessons) {
            return [false, 'Conclua todas as aulas antes de solicitar o certificado.'];
        }

        if ($course->finalTest) {
            $passed = $course->finalTest->attempts()
                ->where('user_id', $userId)
                ->where('passed', true)
                ->where('score', '>=', $course->finalTest->passing_score)
                ->exists();

            if (! $passed) {
                return [false, 'Você precisa atingir a nota mínima no teste final para liberar o certificado.'];
            }
        }

        return [true, null];
    }

    private function resolveBranding(Course $course): CertificateBranding
    {
        return $course->certificateBranding
            ?? CertificateBranding::firstOrCreate(['course_id' => null]);
    }

    private function makePdf(Certificate $certificate, Course $course): Dompdf
    {
        $options = new Options();
        $options->set('defaultFont', 'Inter');
        $dompdf = new Dompdf($options);

        $html = view('learning.certificates.pdf', [
            'certificate' => $certificate,
            'course' => $course,
        ])->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('a4', 'portrait');
        $dompdf->render();

        return $dompdf;
    }
}
