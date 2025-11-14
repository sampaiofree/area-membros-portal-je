<section class="space-y-6"
    x-data="{
        modalCourse: null,
        openModal(course) { this.modalCourse = course; document.body.classList.add('overflow-hidden'); },
        closeModal() { this.modalCourse = null; document.body.classList.remove('overflow-hidden'); }
    }">
    <div class="rounded-card bg-white p-6 shadow-card text-edux-text">
        <p class="text-sm uppercase tracking-wide text-edux-primary">Meus cursos</p>
        <h1 class="font-display text-3xl text-edux-primary">Continue de onde parou</h1>
        <p class="text-slate-600">Use os filtros abaixo para localizar rapidamente uma matrícula.</p>
        <div class="mt-4 grid gap-3 md:grid-cols-3">
            <label class="text-sm font-semibold text-slate-600">
                <span>Buscar</span>
                <input type="search" wire:model.debounce.500ms="search" placeholder="Título do curso"
                    class="mt-1 w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30">
            </label>
            <label class="text-sm font-semibold text-slate-600">
                <span>Status</span>
                <select wire:model="status" class="mt-1 w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30">
                    <option value="all">Todos</option>
                    <option value="running">Em andamento</option>
                    <option value="completed">Concluídos</option>
                </select>
            </label>
        </div>
    </div>

    <div class="relative"
        x-data="{
            isDown: false,
            startX: 0,
            scrollLeft: 0,
            startDrag(event) {
                this.isDown = true;
                this.startX = (event.pageX || event.touches?.[0].pageX) - this.$refs.scroll.offsetLeft;
                this.scrollLeft = this.$refs.scroll.scrollLeft;
            },
            drag(event) {
                if (!this.isDown) return;
                event.preventDefault();
                const x = (event.pageX || event.touches?.[0].pageX) - this.$refs.scroll.offsetLeft;
                const walk = (x - this.startX) * 1.2;
                this.$refs.scroll.scrollLeft = this.scrollLeft - walk;
            },
            stopDrag() {
                this.isDown = false;
            }
        }"
        x-on:mousemove="drag($event)"
        x-on:mouseup="stopDrag"
        x-on:mouseleave="stopDrag">
        <div class="flex gap-5 overflow-x-auto pb-4" x-ref="scroll" x-on:mousedown="startDrag($event)" x-on:touchstart="startDrag($event)"
            x-on:touchmove="drag($event)" x-on:touchend="stopDrag">
            @forelse ($enrollments as $enrollment)
                @php
                    $course = $enrollment->course;
                    $progress = $enrollment->progress_percent ?: $course->completionPercentageFor($user);
                    $nextLesson = $course->nextLessonFor($user);
                    $firstLesson = $course->modules->sortBy('position')->flatMap(fn ($module) => $module->lessons->sortBy('position'))->first();
                    $hasCertificate = $course->certificates->isNotEmpty();
                @endphp
                <article class="min-w-[320px] max-w-sm flex-1 rounded-card bg-white p-5 shadow-card transition hover:-translate-y-1"
                    wire:key="enrollment-{{ $enrollment->id }}">
                    <header class="border-b border-edux-line pb-3">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Curso</p>
                        <h2 class="text-xl font-display text-edux-primary">{{ $course->title }}</h2>
                        <p class="text-sm text-slate-500">{{ $course->summary }}</p>
                    </header>

                    <div class="mt-4 space-y-2 rounded-2xl border border-edux-line/70 bg-edux-background px-4 py-3">
                        <div class="flex items-center justify-between text-sm text-slate-600">
                            <span>Seu progresso</span>
                            <span class="font-semibold text-emerald-600">{{ $progress }}%</span>
                        </div>
                        <div class="h-3 rounded-full bg-edux-line">
                            <div class="h-full rounded-full bg-gradient-to-r from-emerald-400 to-emerald-600 transition-all"
                                style="width: {{ $progress }}%"></div>
                        </div>
                    </div>

                    <div class="mt-4 space-y-3">
                        @if ($nextLesson)
                            <div class="rounded-2xl border border-edux-line/70 bg-edux-background/80 px-4 py-3">
                                <p class="text-xs uppercase tracking-wide text-edux-primary">Próxima aula</p>
                                <p class="text-sm font-semibold text-slate-700">{{ $nextLesson->title }}</p>
                            </div>
                            <a href="{{ route('learning.courses.lessons.show', [$course, $nextLesson]) }}" class="edux-btn w-full">
                                Continuar
                            </a>
                            <button type="button" class="edux-btn w-full bg-white text-edux-primary"
                                @click="openModal(@js([
                                    'title' => $course->title,
                                    'summary' => $course->summary,
                                    'lessons' => $course->modules->flatMap->lessons->count(),
                                    'progress' => $progress,
                                ]))">
                                Detalhes
                            </button>
                        @else
                            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-center">
                                <p class="text-lg font-display text-emerald-600">Parabéns!</p>
                                <p class="text-sm text-emerald-700">Você concluiu todas as aulas.</p>
                            </div>
                            @if ($firstLesson)
                                <a href="{{ route('learning.courses.lessons.show', [$course, $firstLesson]) }}"
                                    class="edux-btn w-full bg-white text-edux-primary">
                                    Reassistir aulas
                                </a>
                                <a href="{{ route('learning.courses.lessons.show', [$course, $firstLesson]) }}"
                                    class="edux-btn w-full">
                                    Emitir certificado
                                </a>
                            @endif
                            @if (! $hasCertificate)
                                <small class="block text-center text-xs text-amber-600">Emita o certificado para concluir o curso oficialmente.</small>
                            @endif
                            @if ($user->name_change_available)
                                <small class="text-center text-sm text-slate-500">
                                    Nome incorreto? <a href="{{ route('account.edit') }}" class="font-semibold text-edux-primary underline">Atualize aqui</a>.
                                </small>
                            @endif
                        @endif
                    </div>
                </article>
            @empty
                <div class="rounded-card bg-white p-6 text-center text-slate-500 shadow-card">
                    Você ainda não possui matrículas ativas.
                </div>
            @endforelse
        </div>
        <p class="mt-2 text-center text-xs text-slate-500">Clique e arraste para navegar horizontalmente.</p>
    </div>

    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4"
        x-show="modalCourse"
        x-transition>
        <article class="w-full max-w-lg rounded-card bg-white p-6 shadow-card" @click.away="closeModal">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wide text-edux-primary">Resumo do curso</p>
                    <h3 class="font-display text-2xl text-edux-primary" x-text="modalCourse?.title"></h3>
                </div>
                <button class="text-slate-500" @click="closeModal">&times;</button>
            </div>
            <p class="mt-4 text-sm text-slate-600" x-text="modalCourse?.summary || 'Sem resumo disponível.'"></p>
            <dl class="mt-4 grid grid-cols-2 gap-3 text-sm">
                <div class="rounded-xl bg-edux-background p-3 text-center">
                    <dt class="text-xs uppercase text-slate-500">Aulas</dt>
                    <dd class="text-xl font-display text-edux-primary" x-text="modalCourse?.lessons ?? '—'"></dd>
                </div>
                <div class="rounded-xl bg-edux-background p-3 text-center">
                    <dt class="text-xs uppercase text-slate-500">Progresso</dt>
                    <dd class="text-xl font-display text-edux-primary" x-text="modalCourse?.progress ? modalCourse.progress + '%' : '—'"></dd>
                </div>
            </dl>
            <button class="edux-btn mt-6 w-full" @click="closeModal">Fechar</button>
        </article>
    </div>
</section>
