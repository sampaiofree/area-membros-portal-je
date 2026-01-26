<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\CertificateBranding;
use App\Models\Course;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class GeneratedCertificateController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index(Request $request): View
    {
        $search = (string) $request->query('search', '');
        $isNumericSearch = $search !== '' && is_numeric($search);

        $certificates = Certificate::query()
            ->with(['course', 'user'])
            ->when($search !== '', function ($query) use ($search, $isNumericSearch) {
                $query->where(function ($sub) use ($search, $isNumericSearch) {
                    $sub->where('number', 'like', "%{$search}%")
                        ->orWhere('public_token', 'like', "%{$search}%")
                        ->orWhereHas('course', function ($course) use ($search, $isNumericSearch) {
                            $course->where('title', 'like', "%{$search}%")
                                ->orWhere('slug', 'like', "%{$search}%");

                            if ($isNumericSearch) {
                                $course->orWhere('id', (int) $search);
                            }
                        })
                        ->orWhereHas('user', function ($user) use ($search, $isNumericSearch) {
                            $user->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%")
                                ->orWhere('whatsapp', 'like', "%{$search}%");

                            if ($isNumericSearch) {
                                $user->orWhere('id', (int) $search);
                            }
                        });

                    if ($isNumericSearch) {
                        $sub->orWhere('id', (int) $search)
                            ->orWhere('course_id', (int) $search)
                            ->orWhere('user_id', (int) $search);
                    }
                });
            })
            ->orderByDesc('issued_at')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.certificates.generated', compact('certificates', 'search'));
    }

    public function download(Request $request, Certificate $certificate)
    {
        $certificate->loadMissing(['course.certificateBranding', 'user']);
        $course = $certificate->course;

        if (! $course instanceof Course) {
            abort(404);
        }

        $pdf = $this->makePdf($certificate, $course);

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="certificado-'.$course->slug.'.pdf"',
        ]);
    }

    private function resolveBranding(Course $course): CertificateBranding
    {
        return $course->certificateBranding
            ?? CertificateBranding::firstOrCreate(['course_id' => null]);
    }

    private function makePdf(Certificate $certificate, Course $course): Dompdf
    {
        $certificate->loadMissing('user');
        $options = new Options();
        $options->set('defaultFont', 'Inter');
        $options->setIsRemoteEnabled(true);
        $dompdf = new Dompdf($options);

        $branding = $this->resolveBranding($course);
        $publicUrl = $certificate->public_token
            ? route('certificates.verify', $certificate->public_token)
            : null;
        $qrDataUri = $this->qrDataUri($publicUrl);
        $frontContent = view('learning.certificates.templates.front', [
            'course' => $course,
            'branding' => $branding,
            'displayName' => $certificate->user->preferredName(),
            'issuedAt' => $certificate->issued_at ?? now(),
            'publicUrl' => $publicUrl,
            'qrDataUri' => $qrDataUri,
            'mode' => 'pdf',
        ])->render();

        $backContent = view('learning.certificates.templates.back', [
            'course' => $course,
            'branding' => $branding,
            'mode' => 'pdf',
        ])->render();

        $html = view('learning.certificates.pdf', compact('frontContent', 'backContent'))->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('a4', 'landscape');
        $dompdf->render();

        return $dompdf;
    }

    private function qrDataUri(?string $publicUrl): ?string
    {
        if (! $publicUrl) {
            return null;
        }

        try {
            $response = Http::withoutVerifying()->timeout(5)->get('https://api.qrserver.com/v1/create-qr-code/', [
                'size' => '240x240',
                'data' => $publicUrl,
            ]);

            if (! $response->successful()) {
                return null;
            }

            return 'data:image/png;base64,' . base64_encode($response->body());
        } catch (\Throwable $exception) {
            return null;
        }
    }
}
