<?php

namespace App\Livewire\Admin;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class SystemAssetsManager extends Component
{
    use WithFileUploads;

    public SystemSetting $settings;

    /** @var array<string, mixed> */
    public array $uploads = [
        'favicon' => null,
        'logo' => null,
        'logo_dark' => null,
        'course' => null,
        'module' => null,
        'lesson' => null,
        'certificate_front' => null,
        'certificate_back' => null,
    ];

    protected array $fieldMap = [
        'favicon' => [
            'column' => 'favicon_path',
            'label' => 'Favicon',
            'hint' => 'PNG/SVG até 256 KB',
        ],
        'logo' => [
            'column' => 'default_logo_path',
            'label' => 'Logo padrão',
            'hint' => 'PNG transparente até 512 KB',
        ],
        'logo_dark' => [
            'column' => 'default_logo_dark_path',
            'label' => 'Logo (versão dark)',
            'hint' => 'PNG branco translúcido para fundos escuros',
        ],
        'course' => [
            'column' => 'default_course_cover_path',
            'label' => 'Imagem padrão do curso',
            'hint' => 'Sugestão 1280x720 px',
        ],
        'module' => [
            'column' => 'default_module_cover_path',
            'label' => 'Imagem padrão do módulo',
            'hint' => 'Sugestão 800x400 px',
        ],
        'lesson' => [
            'column' => 'default_lesson_cover_path',
            'label' => 'Imagem padrão da aula',
            'hint' => 'Sugestão 800x400 px',
        ],
        'certificate_front' => [
            'column' => 'default_certificate_front_path',
            'label' => 'Fundo frente do certificado',
            'hint' => 'Sugestão A4 paisagem',
        ],
        'certificate_back' => [
            'column' => 'default_certificate_back_path',
            'label' => 'Fundo verso do certificado',
            'hint' => 'Sugestão A4 paisagem',
        ],
    ];

    protected function rules(): array
    {
        return [
            'uploads.favicon' => ['nullable', 'image', 'max:256'],
            'uploads.logo' => ['nullable', 'image', 'max:512'],
            'uploads.logo_dark' => ['nullable', 'image', 'max:512'],
            'uploads.course' => ['nullable', 'image', 'max:1024'],
            'uploads.module' => ['nullable', 'image', 'max:1024'],
            'uploads.lesson' => ['nullable', 'image', 'max:1024'],
            'uploads.certificate_front' => ['nullable', 'image', 'max:2048'],
            'uploads.certificate_back' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function mount(): void
    {
        $this->settings = SystemSetting::current();
    }

    public function updatedUploads(): void
    {
        // Reseta mensagens de sucesso sempre que novo arquivo é escolhido.
        session()->forget('status');
    }

    public function save(string $field): void
    {
        if (! array_key_exists($field, $this->fieldMap)) {
            return;
        }

        $this->validateOnly("uploads.$field");

        $file = $this->uploads[$field];

        if (! $file) {
            session()->flash('status', 'Selecione um arquivo para continuar.');
            return;
        }

        $column = $this->fieldMap[$field]['column'];

        if ($old = $this->settings->{$column}) {
            Storage::disk('public')->delete($old);
        }

        $path = $file->store('system-assets', 'public');
        $this->settings->update([$column => $path]);

        $this->uploads[$field] = null;

        session()->flash('status', $this->fieldMap[$field]['label'].' atualizado.');
    }

    public function render()
    {
        return view('livewire.admin.system-assets-manager', [
            'fields' => $this->fieldMap,
            'settings' => $this->settings,
        ]);
    }
}
