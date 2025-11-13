@extends('layouts.app')

@section('title', $lesson->title)

@section('content')
    @php
        $youtubeId = null;
        if ($lesson->video_url) {
            $patterns = [
                '/(?:youtube\.com\/watch\?v=|youtube\.com\/embed\/|youtu\.be\/)([A-Za-z0-9_\-]+)/',
                '/youtube\.com\/shorts\/([A-Za-z0-9_\-]+)/',
            ];
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $lesson->video_url, $matches)) {
                    $youtubeId = $matches[1];
                    break;
                }
            }
        }
    @endphp

    <style>
        .plyr__video-wrapper iframe{
            width: 600% !important; 
            margin-left: -250% !important;
        }
    </style>

    <section class="space-y-6" x-data="{ showLessons: false }">
        <header class="rounded-card bg-white p-5 shadow-card">
            <p class="text-xs uppercase tracking-wide text-slate-500">{{ $course->title }}</p>
            <h1 class="font-display text-3xl text-edux-primary">{{ $lesson->title }}</h1>
            <div class="mt-2 flex flex-wrap items-center justify-between gap-3 text-sm text-slate-500">
                <span>Módulo {{ $lesson->module->position }} · Aula {{ $lesson->position }}</span>
                <span class="font-semibold text-edux-primary">{{ $progressPercent }}% concluído</span>
            </div>
        </header>

        <div class="rounded-card bg-black shadow-card">
            @if ($youtubeId)
                <div class="plyr__video-embed" id="lesson-player">
                    <iframe src="https://www.youtube.com/embed/{{ $youtubeId }}?modestbranding=1&rel=0" allowfullscreen allow="autoplay"></iframe>
                </div>
            @elseif ($lesson->video_url)
                <iframe class="h-64 w-full rounded-card md:h-[420px]" src="{{ $lesson->video_url }}" allowfullscreen loading="lazy"></iframe>
            @elseif ($lesson->content)
                <div class="rounded-card bg-white p-6 text-slate-700">
                    {!! nl2br(e($lesson->content)) !!}
                </div>
            @else
                <div class="rounded-card bg-white p-6 text-slate-700">
                    Conteúdo desta aula será disponibilizado em breve.
                </div>
            @endif
        </div>

        <div class="rounded-card bg-white p-6 shadow-card space-y-4">
            @unless ($isCompleted)
                <form method="POST" action="{{ route('learning.courses.lessons.complete', [$course, $lesson]) }}">
                    @csrf
                    <button type="submit" class="edux-btn w-full">✅ Marcar aula como concluída</button>
                </form>
            @else
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-center text-emerald-600">
                    Aula concluída em sua linha do tempo.
                </div>
            @endunless

            <button type="button" class="edux-btn w-full bg-white text-edux-primary" @click="showLessons = true">
                📖 Lista de aulas
            </button>

            @if ($course->finalTest)
                <a href="{{ route('learning.courses.final-test.intro', $course) }}" class="edux-btn w-full bg-white text-edux-primary">
                    📝 Ir para o teste final
                </a>
            @endif

            <form method="POST" action="{{ route('learning.courses.certificate.store', $course) }}">
                @csrf
                <button type="submit" class="edux-btn w-full">🏆 Receber certificado</button>
            </form>
            @if (auth()->user()->name_change_available)
                <small class="block text-center text-sm text-slate-500">
                    Nome incorreto? <a href="{{ route('learning.profile.name.edit') }}" class="font-semibold text-edux-primary underline">Atualize aqui</a>.
                </small>
            @endif

            <div class="flex flex-wrap gap-3">
                @if ($previousLesson)
                    <a href="{{ route('learning.courses.lessons.show', [$course, $previousLesson]) }}" class="edux-btn flex-1 bg-white text-edux-primary">
                        ← Aula anterior
                    </a>
                @endif
                @if ($nextLesson)
                    <a href="{{ route('learning.courses.lessons.show', [$course, $nextLesson]) }}" class="edux-btn flex-1">
                        Próxima aula →
                    </a>
                @endif
            </div>
        </div>

        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4" x-show="showLessons" x-transition>
            <article class="w-full max-w-2xl rounded-card bg-white p-6 shadow-card">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="font-display text-2xl text-edux-primary">Mapa do curso</h2>
                    <button class="text-slate-500" @click="showLessons = false">&times;</button>
                </div>
                <div class="max-h-[70vh] space-y-4 overflow-y-auto pr-2">
                    @foreach ($course->modules as $module)
                        <div class="rounded-2xl border border-edux-line/70 p-4">
                            <button type="button" class="flex w-full items-center justify-between text-left font-semibold text-slate-700" x-data="{ open: false }" @click="open = !open">
                                <span>Módulo {{ $module->position }} · {{ $module->title }}</span>
                                <svg class="h-5 w-5 text-edux-primary transition" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <ul class="mt-3 space-y-2 text-sm" x-show="open" x-collapse>
                                @foreach ($module->lessons as $moduleLesson)
                                    @php
                                        $completed = in_array($moduleLesson->id, $completedLessonIds, true);
                                        $isActive = $moduleLesson->id === $lesson->id;
                                    @endphp
                                    <li>
                                        <a href="{{ route('learning.courses.lessons.show', [$course, $moduleLesson]) }}"
                                            @class([
                                                'flex items-center justify-between rounded-xl border px-4 py-2 transition',
                                                'border-edux-primary bg-edux-background font-semibold' => $isActive,
                                                'border-edux-line hover:border-edux-primary/60' => ! $isActive,
                                            ])>
                                            <span>{{ $moduleLesson->position }}. {{ $moduleLesson->title }}</span>
                                            @if ($completed)
                                                <span class="text-emerald-500">✓</span>
                                            @endif
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </article>
        </div>
    </section>

    @if ($youtubeId)
        <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css">
        <script src="https://cdn.plyr.io/3.7.8/plyr.polyfilled.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                if (window.Plyr) {
                    new Plyr('#lesson-player', { youtube: { rel: 0, modestbranding: 1 } });
                }
            });
        </script>
    @endif
@endsection
