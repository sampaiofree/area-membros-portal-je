@extends('layouts.app')

@section('title', 'Teste final - '.$course->title)

@section('content')
    <section class="space-y-6">
        <header class="rounded-card bg-white p-6 shadow-card flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div class="space-y-1">
                <p class="text-sm uppercase tracking-[0.2em] text-edux-primary">Teste final e certificacao</p>
                <h1 class="font-display text-3xl text-edux-primary break-words">{{ $course->title }}</h1>
                <p class="text-slate-600 text-sm max-w-2xl">
                    Configure regras, cadastre questoes e personalize alternativas em um unico lugar sem sair do painel do curso.
                </p>
            </div>
            <div class="flex flex-wrap gap-3 text-sm">
                <a href="{{ route('courses.modules.edit', $course) }}" class="edux-btn bg-white text-edux-primary">
                    Modulos e aulas
                </a>
                <a href="{{ route('courses.edit', $course) }}" class="edux-btn bg-white text-edux-primary">
                    Voltar ao resumo
                </a>
            </div>
        </header>

        <livewire:admin.final-test-manager :course-id="$course->id" />
    </section>
@endsection
