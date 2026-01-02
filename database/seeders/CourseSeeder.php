<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\FinalTest;
use App\Models\FinalTestQuestion;
use App\Models\FinalTestQuestionOption;
use App\Models\LessonCompletion;
use App\Models\Module;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@edux.test'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('password'),
                'role' => UserRole::ADMIN->value,
                'email_verified_at' => now(),
            ]
        );

        $courseData = [
            'title' => 'Fundamentos do E-Learning com Laravel',
            'summary' => 'Aprenda a estruturar cursos, módulos, aulas e testes finais usando Laravel.',
            'description' => 'Este curso acompanha todo o fluxo de criação de um treinamento EAD dentro do Edux, desde o planejamento dos módulos até a configuração de testes finais e emissão de certificados.',
            'status' => 'published',
            'duration_minutes' => 180,
            'published_at' => now()->subDays(5),
        ];

        $slug = Str::slug($courseData['title']);

        $course = Course::updateOrCreate(
            ['slug' => $slug],
            array_merge($courseData, [
                'owner_id' => $admin->id,
                'slug' => $slug,
            ])
        );

        $modules = [
            [
                'title' => 'Planejamento e Configuração',
                'description' => 'Configuração do ambiente local, análise de requisitos e planejamento do curso.',
                'lessons' => [
                    [
                        'title' => 'Visão geral da plataforma',
                        'content' => 'Apresentação do Edux, perfis de usuário e recursos disponíveis.',
                        'duration_minutes' => 15,
                    ],
                    [
                        'title' => 'Preparando o ambiente Laravel',
                        'content' => 'Instalação, configuração do .env e execução das migrações iniciais.',
                        'duration_minutes' => 25,
                    ],
                ],
            ],
            [
                'title' => 'Modelagem de Conteúdo',
                'description' => 'Como estruturar cursos, módulos e aulas reutilizáveis.',
                'lessons' => [
                    [
                        'title' => 'Criando cursos e módulos',
                        'content' => 'Demonstração prática da tela de criação de cursos e módulos.',
                        'duration_minutes' => 30,
                    ],
                    [
                        'title' => 'Cadastro de aulas',
                        'content' => 'Boas práticas para descrição de aulas e anexos.',
                        'duration_minutes' => 35,
                    ],
                ],
            ],
            [
                'title' => 'Avaliações e Certificação',
                'description' => 'Processo de criação de testes finais e critérios de aprovação.',
                'lessons' => [
                    [
                        'title' => 'Configurando o teste final',
                        'content' => 'Definição de nota mínima, tentativas e duração.',
                        'duration_minutes' => 20,
                    ],
                    [
                        'title' => 'Emitindo certificados',
                        'content' => 'Fluxo para registrar aprovação e emitir certificado.',
                        'duration_minutes' => 25,
                    ],
                ],
            ],
        ];

        foreach ($modules as $index => $moduleData) {
            $module = Module::updateOrCreate(
                [
                    'course_id' => $course->id,
                    'title' => $moduleData['title'],
                ],
                [
                    'description' => $moduleData['description'],
                    'position' => $index + 1,
                ]
            );

            foreach ($moduleData['lessons'] as $lessonIndex => $lessonData) {
                $module->lessons()->updateOrCreate(
                    ['title' => $lessonData['title']],
                    [
                        'content' => $lessonData['content'],
                        'duration_minutes' => $lessonData['duration_minutes'],
                        'position' => $lessonIndex + 1,
                    ]
                );
            }
        }

        FinalTest::updateOrCreate(
            ['course_id' => $course->id],
            [
                'title' => 'Avaliação final · Fundamentos do E-Learning',
                'instructions' => 'Responda às questões sobre planejamento, criação de conteúdo e configurações de testes. Nota mínima de 70% para aprovação.',
                'passing_score' => 70,
                'max_attempts' => 2,
                'duration_minutes' => 30,
            ]
        );
        $finalTest = $course->finalTest()->first();

        if ($finalTest && $finalTest->questions()->count() === 0) {
            $questionData = [
                [
                    'title' => 'Quais perfis existem no Edux?',
                    'statement' => 'Selecione a alternativa correta sobre os perfis com acesso ao painel.',
                    'options' => [
                        ['label' => 'Administrador e Aluno', 'is_correct' => true],
                        ['label' => 'Administrador e Visitante', 'is_correct' => false],
                        ['label' => 'Somente aluno', 'is_correct' => false],
                    ],
                ],
                [
                    'title' => 'O que o teste final valida?',
                    'statement' => 'Marque a opção correta.',
                    'options' => [
                        ['label' => 'Que todas as aulas foram assistidas e a nota mínima foi atingida.', 'is_correct' => true],
                        ['label' => 'Que o certificado foi emitido automaticamente.', 'is_correct' => false],
                        ['label' => 'Que o aluno cadastrou um curso', 'is_correct' => false],
                    ],
                ],
            ];

            foreach ($questionData as $index => $data) {
                $question = FinalTestQuestion::updateOrCreate(
                    [
                        'final_test_id' => $finalTest->id,
                        'title' => $data['title'],
                    ],
                    [
                        'statement' => $data['statement'],
                        'position' => $index + 1,
                        'weight' => 1,
                    ]
                );

                foreach ($data['options'] as $optionIndex => $optionData) {
                    FinalTestQuestionOption::updateOrCreate(
                        [
                            'final_test_question_id' => $question->id,
                            'label' => $optionData['label'],
                        ],
                        [
                            'is_correct' => $optionData['is_correct'],
                            'position' => $optionIndex + 1,
                        ]
                    );
                }
            }
        }

        $student = User::firstOrCreate(
            ['email' => 'aluno@edux.test'],
            [
                'name' => 'Aluno Diego',
                'password' => Hash::make('password'),
                'role' => UserRole::STUDENT->value,
                'email_verified_at' => now(),
            ]
        );

        $enrollment = Enrollment::firstOrCreate(
            [
                'course_id' => $course->id,
                'user_id' => $student->id,
            ]
        );

        $firstLesson = $course->lessons()->orderBy('id')->first();

        if ($firstLesson) {
            LessonCompletion::updateOrCreate(
                [
                    'lesson_id' => $firstLesson->id,
                    'user_id' => $student->id,
                ],
                ['completed_at' => now()]
            );

            $enrollment->recalculateProgress();
        }
    }
}
