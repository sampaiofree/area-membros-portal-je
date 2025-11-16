<section class="space-y-6 pb-32" x-data="{ showLessons: false, moreOpen: false }">
    <header class="rounded-card bg-white p-5 shadow-card">
        <p class="text-xs uppercase tracking-wide text-slate-500">{{ $course->title }}</p>
        <h1 class="font-display text-3xl text-edux-primary">{{ $lesson->title }}</h1>
        <div class="mt-2 flex flex-wrap items-center justify-between gap-3 text-sm text-slate-500">
            <span>Modulo {{ $lesson->module->position }} / Aula {{ $lesson->position }}</span>
            <span class="font-semibold text-edux-primary">{{ $progressPercent }}% concluido</span>
        </div>
    </header>

    <div class="rounded-card bg-black shadow-card">
        @if ($this->youtubeId)
            <div class="plyr__video-embed" id="lesson-player">
                <iframe src="https://www.youtube.com/embed/{{ $this->youtubeId }}?modestbranding=1&rel=0" allowfullscreen allow="autoplay"></iframe>
            </div>
        @elseif ($lesson->video_url)
            <iframe class="h-64 w-full rounded-card md:h-[420px]" src="{{ $lesson->video_url }}" allowfullscreen loading="lazy"></iframe>
        @elseif ($lesson->content)
            <div class="rounded-card bg-white p-6 text-slate-700">
                {!! nl2br(e($lesson->content)) !!}
            </div>
        @else
            <div class="rounded-card bg-white p-6 text-slate-700">
                Conteudo desta aula sera disponibilizado em breve.
            </div>
        @endif
    </div>

    @if ($statusMessage)
        <div class="rounded-2xl border-l-4 border-emerald-500 bg-emerald-50 p-4 text-emerald-800 shadow-card">
            {{ $statusMessage }}
        </div>
    @endif

    @if ($errorMessage)
        <div class="rounded-2xl border-l-4 border-red-500 bg-red-50 p-4 text-red-800 shadow-card">
            {{ $errorMessage }}
        </div>
    @endif

    <div class="rounded-card bg-white p-6 shadow-card space-y-5">
        <div class="grid grid-cols-2 gap-3">
            @if ($previousLesson)
                <a href="{{ route('learning.courses.lessons.show', [$course, $previousLesson]) }}" class="edux-btn flex h-full items-center justify-center gap-2 bg-white text-edux-primary">
                    <span aria-hidden="true">&larr;</span>
                    <span class="text-sm font-semibold">Aula anterior</span>
                </a>
            @endif
            @if ($nextLesson)
                <a href="{{ route('learning.courses.lessons.show', [$course, $nextLesson]) }}" class="edux-btn flex h-full items-center justify-center gap-2">
                    <span class="text-sm font-semibold">Proxima aula</span>
                    <span aria-hidden="true">&rarr;</span>
                </a>
            @endif
        </div>

        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
            @unless ($isCompleted)
                <button type="button" wire:click="completeLesson" wire:loading.attr="disabled" class="edux-btn aspect-square w-full text-sm font-semibold flex items-center justify-center text-center">
                    <span wire:loading.remove wire:target="completeLesson">Marcar como concluida</span>
                    <span wire:loading wire:target="completeLesson">Salvando...</span>
                </button>
            @else
                <div class="flex aspect-square w-full items-center justify-center rounded-2xl border border-emerald-200 bg-emerald-50 px-3 text-center text-sm font-semibold text-emerald-700">
                    Aula concluida
                </div>
            @endunless

            <button type="button" class="edux-btn aspect-square flex w-full items-center justify-center bg-white text-edux-primary text-sm font-semibold text-center" @click="showLessons = true">
                Lista de aulas
            </button>

            @if ($course->finalTest)
                <a href="{{ route('learning.courses.final-test.intro', $course) }}" class="edux-btn aspect-square flex w-full items-center justify-center bg-white text-edux-primary text-sm font-semibold text-center">
                    Ir para o teste final
                </a>
            @endif

            <button type="button" wire:click="requestCertificate" wire:loading.attr="disabled" @class([
                'edux-btn aspect-square flex w-full items-center justify-center text-sm font-semibold text-center',
                'opacity-70' => ! $hasPaidCertificate,
            ]) @disabled(! $hasPaidCertificate)>
                <span wire:loading.remove wire:target="requestCertificate">Receber certificado</span>
                <span wire:loading wire:target="requestCertificate">Gerando...</span>
            </button>
        </div>

        @if (! $hasPaidCertificate)
            <p class="text-center text-xs text-amber-600">Finalize o pagamento do certificado antes de emitir. Consulte a aba de suporte.</p>
        @endif

        @if ($canRename)
            <small class="block text-center text-sm text-slate-500">
                Nome incorreto? <a href="{{ route('account.edit') }}" class="font-semibold text-edux-primary underline">Atualize aqui</a>.
            </small>
        @endif
    </div>

    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4" x-show="showLessons" x-transition>
        <article class="w-full max-w-2xl rounded-card bg-white p-6 shadow-card">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="font-display text-2xl text-edux-primary">Mapa do curso</h2>
                <button class="text-slate-500" @click="showLessons = false">&times;</button>
            </div>
            <div class="max-h-[70vh] space-y-4 overflow-y-auto pr-2">
                @foreach ($course->modules as $module)
                    <div class="rounded-2xl border border-edux-line/70 p-4" x-data="{ open: {{ $module->id === $lesson->module_id ? 'true' : 'false' }} }">
                        <button type="button" class="flex w-full items-center justify-between text-left font-semibold text-slate-700" @click="open = !open">
                            <span>Modulo {{ $module->position }} - {{ $module->title }}</span>
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
                                            <span class="text-emerald-500">&#10003;</span>
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

    @php
        $navIsCourses = request()->routeIs('learning.courses.*');
        $navIsHome = request()->routeIs('dashboard');
    @endphp
    <nav class="fixed inset-x-0 bottom-0 z-40 pb-4" x-cloak>
        <div class="relative mx-auto max-w-3xl px-4" @click.away="moreOpen = false">
            <div class="flex items-center justify-between gap-2 rounded-3xl bg-white px-4 py-3 shadow-xl">
                <a href="{{ route('dashboard') }}" @class([
                    'flex-1 flex flex-col items-center gap-1',
                    'text-[#1A73E8]' => $navIsHome,
                    'text-[#555]' => ! $navIsHome,
                ])>
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M3 9.75L12 4l9 5.75V20a1 1 0 01-1 1h-5.5v-5.5h-5V21H4a1 1 0 01-1-1V9.75z" />
                    </svg>
                    <span class="text-[11px] font-semibold">Home</span>
                </a>
                <a href="{{ route('dashboard', ['tab' => 'cursos']) }}" @class([
                    'flex-1 flex flex-col items-center gap-1',
                    'text-[#1A73E8]' => $navIsCourses,
                    'text-[#555]' => ! $navIsCourses,
                ])>
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M4.5 6.75h15M4.5 12h15M4.5 17.25h8.25" />
                    </svg>
                    <span class="text-[11px] font-semibold">Cursos</span>
                </a>
                <button type="button" @click="moreOpen = !moreOpen" :class="moreOpen ? 'text-[#1A73E8]' : 'text-[#555]'" class="flex flex-1 flex-col items-center gap-1 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 12h.01M12 12h.01M19 12h.01" />
                    </svg>
                    <span class="text-[11px] font-semibold">Mais</span>
                </button>
            </div>

            <div x-show="moreOpen" x-transition x-cloak class="absolute bottom-full left-0 right-0 mb-2 space-y-2 rounded-2xl bg-white p-3 shadow-lg">
                <a href="{{ route('dashboard', ['tab' => 'vitrine']) }}" class="flex w-full items-center justify-between rounded-xl px-3 py-2 text-sm font-semibold text-[#555] hover:bg-gray-50">
                    <span>Vitrine</span>
                    <span class="text-xs text-[#1A73E8]">Ver</span>
                </a>
                <a href="{{ route('dashboard', ['tab' => 'notificacoes']) }}" class="flex w-full items-center justify-between rounded-xl px-3 py-2 text-sm font-semibold text-[#555] hover:bg-gray-50">
                    <span>Notificacoes</span>
                    <span class="text-xs text-[#1A73E8]">Ver</span>
                </a>
                <a href="{{ route('dashboard', ['tab' => 'suporte']) }}" class="flex w-full items-center justify-between rounded-xl px-3 py-2 text-sm font-semibold text-[#555] hover:bg-gray-50">
                    <span>Suporte</span>
                    <span class="text-xs text-[#1A73E8]">Ver</span>
                </a>
                <a href="{{ route('dashboard', ['tab' => 'conta']) }}" class="flex w-full items-center justify-between rounded-xl px-3 py-2 text-sm font-semibold text-[#555] hover:bg-gray-50">
                    <span>Minha conta</span>
                    <span class="text-xs text-[#1A73E8]">Ver</span>
                </a>
            </div>
        </div>
    </nav>
</section>

@if ($showPaymentModal)
    @php
        $formattedPrice = $course->certificate_price ? number_format($course->certificate_price, 2, ',', '.') : null;
    @endphp
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4">
        <div class="w-full max-w-lg space-y-4 rounded-3xl bg-white p-6 shadow-2xl">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs uppercase tracking-wide text-edux-primary">Pagamento pendente</p>
                    <h3 class="font-display text-2xl text-edux-primary">Finalize para liberar o certificado</h3>
                </div>
                <button type="button" class="text-sm font-semibold text-slate-500 hover:text-edux-primary" wire:click="closePaymentModal">
                    Fechar
                </button>
            </div>
            <p class="text-sm text-slate-600">
                Antes de emitir o certificado e necessario concluir o pagamento referente a este curso.
                @if ($formattedPrice)
                    O valor informado e de <strong>R$ {{ $formattedPrice }}</strong>.
                @endif
            </p>
            <div class="flex flex-wrap gap-3">
                @if ($course->certificate_payment_url)
                    <a href="{{ $course->certificate_payment_url }}" target="_blank" rel="noopener" class="edux-btn">
                        Ir para pagamento
                    </a>
                @endif
                <button type="button" class="edux-btn bg-white text-edux-primary" wire:click="closePaymentModal">
                    Entendi
                </button>
            </div>
            @unless ($course->certificate_payment_url)
                <p class="text-xs text-slate-500">
                    Entre em contato com o suporte para receber o link de pagamento do certificado.
                </p>
            @endunless
        </div>
    </div>
@endif

@if ($this->youtubeId)
    @push('styles')
        <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css">
    @endpush

    @push('scripts')
        <script src="https://cdn.plyr.io/3.7.8/plyr.polyfilled.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                if (window.Plyr) {
                    new Plyr('#lesson-player', { youtube: { rel: 0, modestbranding: 1 } });
                }
            });
        </script>
    @endpush
@endif
