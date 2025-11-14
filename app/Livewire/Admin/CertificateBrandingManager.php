<?php

namespace App\Livewire\Admin;

use App\Models\CertificateBranding;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class CertificateBrandingManager extends Component
{
    use WithFileUploads;

    public ?CertificateBranding $branding = null;
    public $front_background;
    public $back_background;

    public function mount(): void
    {
        $this->branding = CertificateBranding::firstOrCreate(['course_id' => null]);
    }

    public function save(): void
    {
        $this->validate([
            'front_background' => ['nullable', 'image', 'max:4096'],
            'back_background' => ['nullable', 'image', 'max:4096'],
        ]);

        $data = [];

        if ($this->front_background) {
            $this->deleteFile($this->branding->front_background_path);
            $data['front_background_path'] = $this->front_background->store('certificate-backgrounds', 'public');
        }

        if ($this->back_background) {
            $this->deleteFile($this->branding->back_background_path);
            $data['back_background_path'] = $this->back_background->store('certificate-backgrounds', 'public');
        }

        if ($data) {
            $this->branding->update($data);
            $this->branding->refresh();
            $this->reset(['front_background', 'back_background']);
        }

        session()->flash('status', 'Modelos padrão atualizados.');
    }

    public function deleteFront(): void
    {
        if (! $this->branding->front_background_path) {
            return;
        }

        $this->deleteFile($this->branding->front_background_path);
        $this->branding->update(['front_background_path' => null]);
        $this->branding->refresh();
        $this->front_background = null;
    }

    public function deleteBack(): void
    {
        if (! $this->branding->back_background_path) {
            return;
        }

        $this->deleteFile($this->branding->back_background_path);
        $this->branding->update(['back_background_path' => null]);
        $this->branding->refresh();
        $this->back_background = null;
    }

    public function render()
    {
        return view('livewire.admin.certificate-branding-manager');
    }

    private function deleteFile(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
