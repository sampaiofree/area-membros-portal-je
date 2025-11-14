@extends('layouts.app')

@section('title', 'Teste final - '.$course->title)

@section('content')
    <section class="space-y-6">
        <header class="rounded-card bg-white p-6 shadow-card flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-sm uppercase tracking-wide text-edux-primary">Teste final e certificação</p>
                <h1 class="font-display text-3xl text-edux-primary break-words">{{ $course->title }}</h1>
                <p class="text-slate-600 text-sm">Configure regras do teste, adicione questões e personalize as alternativas. Use os modais para criar/editar sem sair desta tela.</p>
            </div>
            <a href="{{ route('courses.edit', $course) }}" class="edux-btn bg-white text-edux-primary">
                ⬅️ Voltar ao resumo
            </a>
        </header>

        <livewire:admin.final-test-manager :course-id="$course->id" />
    </section>
@endsection
