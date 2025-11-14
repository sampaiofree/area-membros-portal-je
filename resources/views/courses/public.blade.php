@extends('layouts.app')

@section('title', $course->title)

@section('content')
    <article class="space-y-8">
        <section class="rounded-card bg-white shadow-card overflow-hidden">
            @if ($course->promo_video_url)
                <div class="aspect-video w-full">
                    <iframe src="{{ $course->promo_video_url }}" class="h-full w-full" allowfullscreen loading="lazy"></iframe>
                </div>
            @elseif ($course->coverImageUrl())
                <img src="{{ $course->coverImageUrl() }}" alt="{{ $course->title }}" class="h-64 w-full object-cover md:h-96">
            @endif
            <div class="space-y-3 p-6">
                <p class="text-xs uppercase tracking-wide text-edux-primary">Curso online</p>
                <h1 class="font-display text-3xl text-edux-primary">{{ $course->title }}</h1>
                <p class="text-slate-600">{{ $course->description }}</p>
                <div class="flex flex-wrap gap-4 text-sm text-slate-500">
                    <span>Alunos: <strong class="text-edux-primary">{{ $studentCount }}</strong></span>
                    <span>Carga horária: <strong class="text-edux-primary">{{ $course->duration_minutes ?? '---' }} min</strong></span>
                </div>
                <a href="{{ auth()->check() ? route('learning.courses.show', $course) : route('login') }}"
                    class="edux-btn mt-4 inline-flex">Inscreva-se grátis</a>
            </div>
        </section>

        <section class="rounded-card bg-white p-6 shadow-card space-y-4">
            <h2 class="text-2xl font-display text-edux-primary">Conteúdo programático</h2>
            <div class="space-y-3">
                @foreach ($course->modules as $module)
                    <article class="rounded-2xl border border-edux-line/70 p-4">
                        <p class="text-sm font-semibold text-edux-primary">Módulo {{ $module->position }} · {{ $module->title }}</p>
                        <ul class="mt-2 space-y-1 text-sm text-slate-600">
                            @foreach ($module->lessons as $lesson)
                                <li>• {{ $lesson->title }}</li>
                            @endforeach
                        </ul>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="rounded-card bg-white p-6 shadow-card space-y-4">
            <h2 class="text-2xl font-display text-edux-primary">Modelo do certificado</h2>
            <div class="rounded-2xl border border-edux-line/70 bg-edux-background p-4">
                @if ($course->certificateBranding?->front_background_url)
                    <img src="{{ $course->certificateBranding->front_background_url }}" alt="Modelo do certificado" class="w-full rounded-xl object-cover">
                @else
                    <p class="text-sm text-slate-500">Assim que concluir o curso você recebe o certificado personalizado do EduX.</p>
                @endif
            </div>
        </section>

        <section class="rounded-card bg-white p-6 shadow-card space-y-4">
            <h2 class="text-2xl font-display text-edux-primary">Perguntas frequentes</h2>
            @foreach ([
                ['title' => 'O curso é totalmente gratuito?', 'body' => 'Sim, você pode assistir todas as aulas sem custo. Apenas o certificado exige pagamento quando disponível.'],
                ['title' => 'Por quanto tempo terei acesso?', 'body' => 'Enquanto mantivermos o curso publicado você poderá reassistir quantas vezes quiser.'],
                ['title' => 'Preciso fazer prova?', 'body' => $course->finalTest ? 'Sim, o curso possui teste final para liberar o certificado.' : 'Não, basta concluir todas as aulas.'],
            ] as $faq)
                <details class="rounded-2xl border border-edux-line/70 p-4">
                    <summary class="cursor-pointer text-sm font-semibold text-edux-primary">{{ $faq['title'] }}</summary>
                    <p class="mt-2 text-sm text-slate-600">{{ $faq['body'] }}</p>
                </details>
            @endforeach
        </section>
    </article>

    <div class="fixed inset-x-0 bottom-0 z-40 border-t border-edux-line bg-white p-4 shadow-2xl md:hidden">
        <a href="{{ auth()->check() ? route('learning.courses.show', $course) : route('login') }}" class="edux-btn w-full">Inscreva-se grátis</a>
    </div>
@endsection
