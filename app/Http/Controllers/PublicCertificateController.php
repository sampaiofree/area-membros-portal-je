<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\View\View;

class PublicCertificateController extends Controller
{
    public function __invoke(string $token): View
    {
        $certificate = Certificate::with(['course.owner', 'user'])
            ->where('public_token', $token)
            ->firstOrFail();

        return view('learning.certificates.public', [
            'certificate' => $certificate,
            'course' => $certificate->course,
            'user' => $certificate->user,
        ]);
    }
}
