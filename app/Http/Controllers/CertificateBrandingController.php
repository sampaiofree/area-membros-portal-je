<?php

namespace App\Http\Controllers;

use App\Models\CertificateBranding;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CertificateBrandingController extends Controller
{
    public function edit(): View
    {
        $branding = CertificateBranding::firstOrCreate(['course_id' => null]);

        return view('certificates.branding.edit', compact('branding'));
    }

    public function update(Request $request): RedirectResponse
    {
        $branding = CertificateBranding::firstOrCreate(['course_id' => null]);

        $validated = $request->validate([
            'front_background' => ['nullable', 'image', 'max:4096'],
            'back_background' => ['nullable', 'image', 'max:4096'],
        ]);

        $data = [];

        if ($request->hasFile('front_background')) {
            $this->deleteFile($branding->front_background_path);
            $data['front_background_path'] = $request->file('front_background')->store('certificate-backgrounds', 'public');
        }

        if ($request->hasFile('back_background')) {
            $this->deleteFile($branding->back_background_path);
            $data['back_background_path'] = $request->file('back_background')->store('certificate-backgrounds', 'public');
        }

        $branding->update($data);

        return back()->with('status', 'Modelos padrão atualizados.');
    }

    private function deleteFile(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
