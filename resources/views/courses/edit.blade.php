@extends('layouts.app')

@section('title', 'Editar curso')

@section('content')
    <section class="rounded-card bg-white p-6 shadow-card">
        <p class="text-sm uppercase tracking-wide text-edux-primary">Editar</p>
        <h1 class="font-display text-3xl text-edux-primary">Curso · {{ $course->title }}</h1>
        <p class="text-slate-600">Atualize dados gerais, módulos e configure o teste final.</p>
    </section>

    @include('courses.partials.form', [
        'action' => route('courses.update', $course),
        'method' => 'PUT',
        'submitLabel' => 'Salvar alterações',
        'course' => $course,
        'teachers' => $teachers,
        'user' => $user,
    ])

    <section class="mt-10 grid gap-6 md:grid-cols-2">
        <div class="rounded-card bg-white p-6 shadow-card" x-data="{ preview: @js($course->modules->pluck('title')) }">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-sm uppercase tracking-wide text-edux-primary">Conteúdo</p>
                    <h2 class="text-2xl font-display text-edux-primary">Módulos</h2>
                </div>
                <a href="{{ route('courses.modules.create', $course) }}" class="edux-btn bg-white text-edux-primary">➕ Novo módulo</a>
            </div>

            <p class="mt-4 text-sm text-slate-500">Arraste para planejar a sequência (apenas visual).</p>
            <div class="mt-4 flex flex-wrap gap-2" x-sortable x-data x-ref="dragRoot">
                <template x-for="(title, index) in preview" :key="title">
                    <div draggable="true"
                        @dragstart="event.dataTransfer.setData('text/plain', index)"
                        @dragover.prevent
                        @drop="
                            const from = event.dataTransfer.getData('text/plain');
                            const temp = preview[from];
                            preview[from] = preview[index];
                            preview[index] = temp;
                        "
                        class="rounded-full border border-edux-line bg-edux-background px-4 py-2 text-sm font-semibold text-edux-primary">
                        <span x-text="title"></span>
                    </div>
                </template>
            </div>

            <div class="mt-6 space-y-4">
                @forelse ($course->modules as $module)
                    <article class="rounded-2xl border border-edux-line p-4" x-data="{ open: false }">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <h3 class="font-semibold">{{ $module->title }}</h3>
                                <p class="text-xs uppercase tracking-wide text-slate-500">Posição {{ $module->position }}</p>
                            </div>
                            <div class="flex gap-2">
                                <button type="button" class="edux-btn bg-white text-edux-primary" @click="open = !open">
                                    <span x-text="open ? 'Fechar' : 'Ver aulas'"></span>
                                </button>
                                <a href="{{ route('modules.edit', $module) }}" class="edux-btn bg-white text-edux-primary">Editar</a>
                                <form method="POST" action="{{ route('modules.destroy', $module) }}" onsubmit="return confirm('Remover módulo?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="edux-btn bg-red-500 text-white">Excluir</button>
                                </form>
                            </div>
                        </div>

                        <div class="mt-4 border-t border-edux-line pt-4" x-show="open" x-collapse>
                            <div class="mb-3 flex items-center justify-between">
                                <span class="text-sm font-semibold text-slate-700">Aulas ({{ $module->lessons->count() }})</span>
                                <a href="{{ route('modules.lessons.create', $module) }}" class="text-sm font-semibold text-edux-primary underline-offset-2 hover:underline">Nova aula</a>
                            </div>
                            <ul class="space-y-3">
                                @forelse ($module->lessons as $lesson)
                                    <li class="flex flex-col gap-2 rounded-xl border border-edux-line/70 px-3 py-2 sm:flex-row sm:items-center sm:justify-between">
                                        <div>
                                            <p class="font-medium">{{ $lesson->title }}</p>
                                            <p class="text-xs text-slate-500">{{ $lesson->duration_minutes ? $lesson->duration_minutes . ' min' : 'Sem duração' }}</p>
                                        </div>
                                        <div class="flex gap-2">
                                            <a href="{{ route('lessons.edit', $lesson) }}" class="text-sm font-semibold text-edux-primary underline-offset-2 hover:underline">Editar</a>
                                            <form method="POST" action="{{ route('lessons.destroy', $lesson) }}" onsubmit="return confirm('Excluir aula?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm font-semibold text-red-500 hover:underline">Excluir</button>
                                            </form>
                                        </div>
                                    </li>
                                @empty
                                    <li class="text-sm text-slate-500">Nenhuma aula neste módulo.</li>
                                @endforelse
                            </ul>
                        </div>
                    </article>
                @empty
                    <p class="text-sm text-slate-500">Adicione módulos para organizar seu curso.</p>
                @endforelse
            </div>
        </div>

        <div class="rounded-card bg-white p-6 shadow-card space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm uppercase tracking-wide text-edux-primary">Avaliação</p>
                    <h2 class="text-2xl font-display text-edux-primary">Teste final</h2>
                </div>
                <div class="flex gap-2">
                    @if ($course->finalTest)
                        <a href="{{ route('courses.final-test.questions.index', [$course, $course->finalTest]) }}" class="edux-btn bg-white text-edux-primary">Questões</a>
                        <a href="{{ route('courses.final-test.edit', [$course, $course->finalTest]) }}" class="edux-btn bg-white text-edux-primary">Editar</a>
                    @else
                        <a href="{{ route('courses.final-test.create', $course) }}" class="edux-btn bg-white text-edux-primary">Criar</a>
                    @endif
                </div>
            </div>

            <div>
                @if ($course->finalTest)
                    <div class="rounded-xl border border-edux-line bg-edux-background p-4">
                        <p class="font-semibold">{{ $course->finalTest->title }}</p>
                        <p class="text-sm text-slate-600">
                            Nota mínima <strong>{{ $course->finalTest->passing_score }}%</strong> ·
                            Tentativas <strong>{{ $course->finalTest->max_attempts }}</strong> ·
                            Duração <strong>{{ $course->finalTest->duration_minutes ?? '—' }} min</strong>
                        </p>
                    </div>
                    <form method="POST" action="{{ route('courses.final-test.destroy', [$course, $course->finalTest]) }}" class="mt-4" onsubmit="return confirm('Remover teste final?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="edux-btn bg-red-500 text-white">Excluir teste final</button>
                    </form>
                @else
                    <p class="text-sm text-slate-500">Nenhum teste final cadastrado.</p>
                @endif
            </div>
        </div>
    </section>
@endsection
