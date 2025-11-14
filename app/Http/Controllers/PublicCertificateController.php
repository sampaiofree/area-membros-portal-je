<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\CertificateBranding;
use Illuminate\View\View;

class PublicCertificateController extends Controller
{
    public function __invoke(string $token): View
    {
        $certificate = Certificate::with(['course.owner', 'user'])
            ->where('public_token', $token)
            ->firstOrFail();

        $branding = $this->resolveBranding($certificate->course);
        $frontContent = view('learning.certificates.templates.front', [
            'course' => $certificate->course,
            'branding' => $branding,
            'displayName' => $certificate->user->preferredName(),
            'issuedAt' => $certificate->issued_at ?? now(),
            'publicUrl' => route('certificates.verify', $certificate->public_token),
        ])->render();

        $backContent = view('learning.certificates.templates.back', [
            'course' => $certificate->course,
            'branding' => $branding,
        ])->render();

        return view('learning.certificates.public', [
            'certificate' => $certificate,
            'course' => $certificate->course,
            'user' => $certificate->user,
            'frontContent' => $frontContent,
            'backContent' => $backContent,
        ]);
    }

    private function resolveBranding($course): CertificateBranding
    {
        return $course->certificateBranding
            ?? CertificateBranding::firstOrCreate(['course_id' => null]);
    }
}
