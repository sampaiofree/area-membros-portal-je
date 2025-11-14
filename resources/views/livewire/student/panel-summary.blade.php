<section class="space-y-6">
    <div class="rounded-card bg-white p-6 shadow-card">
        <p class="text-sm uppercase tracking-wide text-edux-primary">Bem-vindo</p>
        <h1 class="font-display text-3xl text-edux-primary">Olá, {{ $user->preferredName() }}!</h1>
        <p class="text-slate-600 text-sm">Confira abaixo o status das suas matrículas e os certificados disponíveis.</p>
        <div class="mt-6 grid gap-4 md:grid-cols-3">
            <div class="rounded-2xl border border-edux-line/70 bg-edux-background px-4 py-5 text-center">
                <p class="text-xs uppercase text-slate-500">Matrículas</p>
                <p class="mt-2 text-3xl font-display text-edux-primary">{{ $totalEnrollments }}</p>
            </div>
            <div class="rounded-2xl border border-edux-line/70 bg-edux-background px-4 py-5 text-center">
                <p class="text-xs uppercase text-slate-500">Concluídos</p>
                <p class="mt-2 text-3xl font-display text-emerald-600">{{ $completed }}</p>
            </div>
            <div class="rounded-2xl border border-edux-line/70 bg-edux-background px-4 py-5 text-center">
                <p class="text-xs uppercase text-slate-500">Em andamento</p>
                <p class="mt-2 text-3xl font-display text-amber-500">{{ $running }}</p>
            </div>
        </div>
    </div>

    @if ($pendingCertificates->isNotEmpty())
        <div class="rounded-card border border-amber-200 bg-amber-50 p-6 shadow-card space-y-3">
            <div>
                <p class="text-sm uppercase tracking-wide text-amber-600">Certificados pendentes</p>
                <p class="text-slate-700">Você concluiu {{ $pendingCertificates->count() }} curso(s) mas ainda não emitiu o certificado. Lembre-se de confirmar o pagamento antes de emitir.</p>
            </div>
            <div class="space-y-3">
                @foreach ($pendingCertificates as $enrollment)
                    <div class="rounded-2xl border border-amber-200 bg-white px-4 py-3 flex items-center justify-between flex-wrap gap-3">
                        <div>
                            <p class="font-semibold text-edux-primary">{{ $enrollment->course->title }}</p>
                            <p class="text-sm text-slate-500">Acesse o curso e emita o certificado.</p>
                        </div>
                        @php
                            $firstLesson = $enrollment->course->modules->sortBy('position')->flatMap(fn ($module) => $module->lessons->sortBy('position'))->first();
                        @endphp
                        @if ($firstLesson)
                            <a href="{{ route('learning.courses.lessons.show', [$enrollment->course, $firstLesson]) }}" class="edux-btn bg-white text-edux-primary">
                                Emitir agora
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</section>
