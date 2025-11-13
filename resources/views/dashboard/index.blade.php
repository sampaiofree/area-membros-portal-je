@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <section class="edux-grid">
        <div class="rounded-card bg-white p-5 shadow-card">
            <p class="text-sm text-slate-500">Cursos</p>
            <p class="font-display text-4xl">{{ $stats['courses'] }}</p>
        </div>
        <div class="rounded-card bg-white p-5 shadow-card">
            <p class="text-sm text-slate-500">Módulos</p>
            <p class="font-display text-4xl">{{ $stats['modules'] }}</p>
        </div>
        <div class="rounded-card bg-white p-5 shadow-card">
            <p class="text-sm text-slate-500">Aulas</p>
            <p class="font-display text-4xl">{{ $stats['lessons'] }}</p>
        </div>
        <div class="rounded-card bg-white p-5 shadow-card">
            <p class="text-sm text-slate-500">Testes finais</p>
            <p class="font-display text-4xl">{{ $stats['final_tests'] }}</p>
        </div>
    </section>

    @if ($user->isAdmin() || $user->isTeacher())
        <div class="mt-6">
            <a href="{{ route('courses.create') }}" class="edux-btn">Criar novo curso</a>
        </div>
    @endif

    <div class="mt-6 space-y-6">
        @forelse ($courses as $course)
            <article class="rounded-card bg-white p-6 shadow-card space-y-4">
                <header class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <h2 class="font-display text-2xl text-edux-primary">{{ $course->title }}</h2>
                        <p class="text-sm text-slate-600">{{ $course->summary ?? 'Sem resumo' }}</p>
                        <p class="text-xs uppercase tracking-wide text-slate-500">Proprietário: {{ $course->owner->name }}</p>
                    </div>
                    <div class="text-right">
                        <span @class([
                            'inline-flex rounded-full px-4 py-1 text-xs font-semibold',
                            'bg-amber-100 text-amber-800' => $course->status === 'draft',
                            'bg-emerald-100 text-emerald-800' => $course->status === 'published',
                            'bg-slate-200 text-slate-700' => $course->status === 'archived',
                        ])>
                            {{ ucfirst($course->status) }}
                        </span>
                        <p class="text-xs text-slate-500">Atualizado {{ $course->updated_at->diffForHumans() }}</p>
                    </div>
                </header>

                <section>
                    <div class="flex items-center justify-between gap-3">
                        <strong class="text-sm text-slate-700">Módulos ({{ $course->modules->count() }})</strong>
                        @if ($user->isAdmin() || ($user->isTeacher() && $course->owner_id === $user->id))
                            <a href="{{ route('courses.modules.create', $course) }}" class="text-sm font-semibold text-edux-primary underline-offset-2 hover:underline">Adicionar módulo</a>
                        @endif
                    </div>
                    <div class="mt-3 space-y-3">
                        @forelse ($course->modules as $module)
                            <div class="rounded-xl border border-edux-line px-4 py-3">
                                <div class="flex flex-wrap items-center justify-between gap-3">
                                    <div>
                                        <p class="font-semibold">{{ $module->title }}</p>
                                        <p class="text-xs text-slate-500">{{ $module->lessons->count() }} aulas</p>
                                    </div>
                                    @if ($user->isAdmin() || ($user->isTeacher() && $course->owner_id === $user->id))
                                        <div class="flex gap-3 text-sm font-semibold text-edux-primary">
                                            <a href="{{ route('modules.lessons.create', $module) }}">Nova aula</a>
                                            <a href="{{ route('modules.edit', $module) }}">Editar módulo</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">Nenhum módulo cadastrado.</p>
                        @endforelse
                    </div>
                </section>

                <section>
                    <strong class="text-sm text-slate-700">Teste final</strong>
                    <p class="mt-2 text-sm text-slate-600">
                        @if ($course->finalTest)
                            {{ $course->finalTest->title }} · Nota mínima {{ $course->finalTest->passing_score }}% · Tentativas {{ $course->finalTest->max_attempts }}
                            @if ($user->isAdmin() || ($user->isTeacher() && $course->owner_id === $user->id))
                                <a href="{{ route('courses.final-test.edit', [$course, $course->finalTest]) }}" class="ml-2 text-edux-primary underline-offset-2 hover:underline">Gerenciar</a>
                            @endif
                        @else
                            Nenhum teste final cadastrado.
                            @if ($user->isAdmin() || ($user->isTeacher() && $course->owner_id === $user->id))
                                <a href="{{ route('courses.final-test.create', $course) }}" class="ml-2 text-edux-primary underline-offset-2 hover:underline">Criar teste</a>
                            @endif
                        @endif
                    </p>
                </section>

                @if ($user->isAdmin() || ($user->isTeacher() && $course->owner_id === $user->id))
                    <footer class="flex flex-wrap gap-3">
                        <a href="{{ route('courses.edit', $course) }}" class="edux-btn bg-white text-edux-primary">Editar</a>
                        <form method="POST" action="{{ route('courses.destroy', $course) }}" onsubmit="return confirm('Tem certeza que deseja remover este curso?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="edux-btn bg-red-500 text-white">Excluir</button>
                        </form>
                    </footer>
                @endif
            </article>
        @empty
            <div class="rounded-card bg-white p-6 text-center text-slate-500 shadow-card">
                Nenhum curso disponível para o seu perfil.
            </div>
        @endforelse
    </div>
@endsection
