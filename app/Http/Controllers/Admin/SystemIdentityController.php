<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SystemIdentityController extends Controller
{
    /**
     * @var array<string, array{column:string,label:string,rule:string,description:string}>
     */
    private array $fields = [
        'favicon' => [
            'column' => 'favicon_path',
            'label' => 'Favicon',
            'rule' => 'nullable|image|max:256',
            'description' => 'PNG ou SVG de até 256 KB.',
        ],
        'logo' => [
            'column' => 'default_logo_path',
            'label' => 'Logo padrão',
            'rule' => 'nullable|image|max:512',
            'description' => 'Logo colorida em PNG (transparente) até 512 KB.',
        ],
        'logo_dark' => [
            'column' => 'default_logo_dark_path',
            'label' => 'Logo para fundo escuro',
            'rule' => 'nullable|image|max:512',
            'description' => 'Logo branca/transparente para dark mode.',
        ],
        'course' => [
            'column' => 'default_course_cover_path',
            'label' => 'Imagem padrão do curso',
            'rule' => 'nullable|image|max:1024',
            'description' => 'Sugestão 1280×720 px.',
        ],
        'module' => [
            'column' => 'default_module_cover_path',
            'label' => 'Imagem padrão do módulo',
            'rule' => 'nullable|image|max:1024',
            'description' => 'Sugestão 800×400 px.',
        ],
        'lesson' => [
            'column' => 'default_lesson_cover_path',
            'label' => 'Imagem padrão da aula',
            'rule' => 'nullable|image|max:1024',
            'description' => 'Sugestão 800×400 px.',
        ],
        'certificate_front' => [
            'column' => 'default_certificate_front_path',
            'label' => 'Fundo frente do certificado',
            'rule' => 'nullable|image|max:2048',
            'description' => 'Arte em A4 paisagem.',
        ],
        'certificate_back' => [
            'column' => 'default_certificate_back_path',
            'label' => 'Fundo verso do certificado',
            'rule' => 'nullable|image|max:2048',
            'description' => 'Arte em A4 paisagem.',
        ],
    ];

    public function edit()
    {
        return view('admin.identity', [
            'settings' => SystemSetting::current(),
            'fields' => $this->fields,
        ]);
    }

    public function update(Request $request)
    {
        $rules = [];
        foreach ($this->fields as $key => $data) {
            $rules[$key] = $data['rule'];
        }

        $validated = $request->validate($rules);

        $settings = SystemSetting::current();

        foreach ($this->fields as $input => $data) {
            if (! $request->hasFile($input)) {
                continue;
            }

            $column = $data['column'];

            if ($settings->{$column}) {
                Storage::disk('public')->delete($settings->{$column});
            }

            $path = $request->file($input)->store('system-assets', 'public');
            $settings->{$column} = $path;
        }

        $settings->save();

        return redirect()
            ->route('admin.identity')
            ->with('status', 'Identidade visual atualizada com sucesso.');
    }
}
