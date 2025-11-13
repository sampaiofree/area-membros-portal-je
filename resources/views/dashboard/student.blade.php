@extends('layouts.app')

@section('title', 'Meus cursos')

@section('content')
    <section class="space-y-6" x-data="studentCourses()">
        <div class="rounded-card bg-white p-6 shadow-card">
            <p class="text-sm uppercase tracking-wide text-edux-primary">Olá, {{ $user->preferredName() }}! 👋</p>
            <h1 class="font-display text-3xl text-edux-primary">Continue de onde parou</h1>
            <p class="text-slate-600">Arraste horizontalmente para navegar pelos cursos, abra o modal para ver detalhes ou reassista aulas concluídas.</p>
        </div>

        <div class="relative" x-data="dragScroll()" x-on:mousemove="drag($event)" x-on:mouseup="stopDrag" x-on:mouseleave="stopDrag">
            <div class="flex gap-5 overflow-x-auto pb-4" x-ref="scroll" x-on:mousedown="startDrag($event)" x-on:touchstart="startDrag($event)" x-on:touchmove="drag($event)" x-on:touchend="stopDrag">
                @forelse ($enrollments as $enrollment)
                    @php
                        $course = $enrollment->course;
                        $progress = $enrollment->progress_percent ?: $course->completionPercentageFor($user);
                        $nextLesson = $course->nextLessonFor($user);
                        $firstLesson = $course->modules->sortBy('position')->flatMap(fn ($module) => $module->lessons->sortBy('position'))->first();
                    @endphp
                    <article class="min-w-[320px] max-w-sm flex-1 rounded-card bg-white p-5 shadow-card transition hover:-translate-y-1" draggable="false">
                        <header class="border-b border-edux-line pb-3">
                            <p class="text-xs uppercase tracking-wide text-slate-500">📘 Curso</p>
                            <h2 class="text-xl font-display text-edux-primary">{{ $course->title }}</h2>
                            <p class="text-sm text-slate-600">{{ $course->summary }}</p>
                        </header>

                        <div class="mt-4 space-y-2 rounded-2xl border border-edux-line/70 bg-edux-background px-4 py-3">
                            <div class="flex items-center justify-between text-sm text-slate-600">
                                <span>Seu progresso</span>
                                <span class="font-semibold text-emerald-600">{{ $progress }}%</span>
                            </div>
                            <div class="h-3 rounded-full bg-edux-line">
                                <div class="h-full rounded-full bg-gradient-to-r from-emerald-400 to-emerald-600 transition-all" style="width: {{ $progress }}%"></div>
                            </div>
                        </div>

                        <div class="mt-4 space-y-3">
                            @if ($nextLesson)
                                <div class="rounded-2xl border border-edux-line/70 bg-edux-background/80 px-4 py-3">
                                    <p class="text-xs uppercase tracking-wide text-edux-primary">🎯 Próxima aula</p>
                                    <p class="text-sm font-semibold text-slate-700">{{ $nextLesson->title }}</p>
                                </div>
                                <a href="{{ route('learning.courses.lessons.show', [$course, $nextLesson]) }}" class="edux-btn w-full">
                                    🚀 Continuar
                                </a>
                                <button type="button" class="edux-btn w-full bg-white text-edux-primary"
                                    @click="openModal({ title: '{{ $course->title }}', summary: '{{ e($course->summary) }}', lessons: {{ $course->modules->flatMap->lessons->count() }}, progress: {{ $progress }} })">
                                    ℹ️ Detalhes
                                </button>
                            @else
                                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-center">
                                    <p class="text-lg font-display text-emerald-600">🎉 Parabéns!</p>
                                    <p class="text-sm text-emerald-700">Você concluiu todas as aulas.</p>
                                </div>
                                @if ($firstLesson)
                                    <a href="{{ route('learning.courses.lessons.show', [$course, $firstLesson]) }}" class="edux-btn w-full bg-white text-edux-primary">
                                        🔁 Reassistir aulas
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('learning.courses.certificate.store', $course) }}" class="flex flex-col gap-2">
                                    @csrf
                                    <button type="submit" class="edux-btn w-full">
                                        🏆 Receber certificado
                                    </button>
                                    @if ($user->name_change_available)
                                        <small class="text-center text-sm text-slate-500">
                                            Nome incorreto? <a href="{{ route('learning.profile.name.edit') }}" class="font-semibold text-edux-primary underline">Atualize aqui</a>.
                                        </small>
                                    @endif
                                </form>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="rounded-card bg-white p-6 text-center text-slate-500 shadow-card">Você ainda não possui matrículas ativas.</div>
                @endforelse
            </div>
            <p class="mt-2 text-center text-xs text-slate-500">💡 Clique e arraste para navegar horizontalmente.</p>
        </div>

        <template x-if="modalCourse">
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4" x-show="modalCourse" x-transition>
                <div class="w-full max-w-lg rounded-card bg-white p-6 shadow-card" @click.away="closeModal">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-edux-primary">Resumo do curso</p>
                            <h3 class="font-display text-2xl text-edux-primary" x-text="modalCourse.title"></h3>
                        </div>
                        <button class="text-slate-500" @click="closeModal">&times;</button>
                    </div>
                    <p class="mt-4 text-sm text-slate-600" x-text="modalCourse.summary"></p>
                    <dl class="mt-4 grid grid-cols-2 gap-3 text-sm">
                        <div class="rounded-xl bg-edux-background p-3 text-center">
                            <dt class="text-xs uppercase text-slate-500">Aulas</dt>
                            <dd class="text-xl font-display text-edux-primary" x-text="modalCourse.lessons"></dd>
                        </div>
                        <div class="rounded-xl bg-edux-background p-3 text-center">
                            <dt class="text-xs uppercase text-slate-500">Progresso</dt>
                            <dd class="text-xl font-display text-edux-primary" x-text="modalCourse.progress + '%'"></dd>
                        </div>
                    </dl>
                    <button class="edux-btn mt-6 w-full" @click="closeModal">Fechar</button>
                </div>
            </div>
        </template>
    </section>

    <script>
        function dragScroll() {
            return {
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
                },
            };
        }

        function studentCourses() {
            return {
                modalCourse: null,
                openModal(course) {
                    this.modalCourse = course;
                    document.body.classList.add('overflow-hidden');
                },
                closeModal() {
                    this.modalCourse = null;
                    document.body.classList.remove('overflow-hidden');
                },
            };
        }
    </script>
@endsection
