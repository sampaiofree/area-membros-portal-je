@php use Illuminate\Support\Str; @endphp

<section class="space-y-6 rounded-card bg-white p-6 shadow-card">
    <div class="grid gap-4 md:grid-cols-3">
        <article class="rounded-2xl border border-edux-line/60 p-4">
            <p class="text-xs uppercase text-slate-500">Questoes</p>
            <p class="mt-2 text-3xl font-display text-edux-primary">{{ $metrics['questions'] }}</p>
            <p class="text-xs text-slate-500">Total de questoes cadastradas.</p>
        </article>
        <article class="rounded-2xl border border-edux-line/60 p-4">
            <p class="text-xs uppercase text-slate-500">Nota minima</p>
            <p class="mt-2 text-3xl font-display text-edux-primary">
                {{ $finalTest ? $metrics['passing_score'].'%' : '—' }}
            </p>
            <p class="text-xs text-slate-500">Percentual necessario para aprovacao.</p>
        </article>
        <article class="rounded-2xl border border-edux-line/60 p-4">
            <p class="text-xs uppercase text-slate-500">Tentativas / duracao</p>
            <p class="mt-2 text-3xl font-display text-edux-primary">
                {{ $finalTest ? $metrics['max_attempts'].'x' : '—' }}
                <span class="text-base font-sans text-slate-500">
                    {{ $finalTest && $metrics['duration'] ? $metrics['duration'].' min' : 'Sem limite' }}
                </span>
            </p>
            <p class="text-xs text-slate-500">Quantidade maxima de tentativas e tempo.</p>
        </article>
    </div>

    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <p class="text-sm uppercase tracking-wide text-edux-primary">Configuracao</p>
            <h2 class="text-2xl font-display text-edux-primary">
                {{ $finalTest ? 'Teste final configurado' : 'Nenhum teste cadastrado' }}
            </h2>
            <p class="text-sm text-slate-600 max-w-2xl">
                Defina o teste final antes de liberar certificados. Adicione questoes e alternativas logo abaixo.
            </p>
        </div>
        <div class="flex flex-wrap gap-3">
            @if ($finalTest)
                <button type="button" class="text-sm font-semibold text-slate-500 underline-offset-2 hover:underline"
                    wire:click="$set('confirmingDelete', true)">
                    Remover teste
                </button>
            @endif
            <button type="button" class="edux-btn" wire:click="openForm" wire:loading.attr="disabled">
                {{ $finalTest ? 'Editar teste final' : 'Configurar teste final' }}
            </button>
        </div>
    </div>

    @if (! $finalTest)
        <div class="rounded-2xl border border-dashed border-edux-line/70 bg-edux-background p-5 text-sm text-slate-600">
            Informe titulo, nota minima e tentativas para habilitar o bloco de questoes.
        </div>
    @else
        <article class="rounded-2xl border border-edux-line/70 bg-edux-background p-5 space-y-2">
            <div class="flex flex-wrap items-center gap-3 text-sm text-slate-500">
                <span>Atualizado {{ $finalTest->updated_at->diffForHumans() }}</span>
                <span>•</span>
                <span>{{ $finalTest->questions->count() }} questoes</span>
            </div>
            <h3 class="text-lg font-semibold text-edux-primary">{{ $finalTest->title }}</h3>
            @if ($finalTest->instructions)
                <p class="text-sm text-slate-600">{{ Str::limit(strip_tags($finalTest->instructions), 220) }}</p>
            @endif
        </article>

        <livewire:admin.final-test-questions-manager :final-test-id="$finalTest->id" :key="'final-test-questions-'.$finalTest->id" />
    @endif

    @if ($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">
            <div class="absolute inset-0" wire:click="closeForm"></div>
            <div class="relative z-10 w-full max-w-4xl rounded-3xl bg-white p-6 shadow-2xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-edux-primary">Configuracao</p>
                        <h3 class="text-2xl font-display text-edux-primary">
                            {{ $finalTest ? 'Editar teste final' : 'Novo teste final' }}
                        </h3>
                    </div>
                    <button type="button" class="text-sm font-semibold text-slate-500 hover:text-edux-primary"
                        wire:click="closeForm">
                        Fechar
                    </button>
                </div>
                <form wire:submit.prevent="save" class="mt-6 grid gap-4 md:grid-cols-2">
                    <label class="space-y-2 text-sm font-semibold text-slate-600 md:col-span-2">
                        <span>Titulo</span>
                        <input type="text" wire:model.defer="title" required
                            class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30">
                        @error('title') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </label>
                    <label class="space-y-2 text-sm font-semibold text-slate-600 md:col-span-2">
                        <span>Instrucoes para os alunos</span>
                        <textarea wire:model.defer="instructions" rows="3"
                            class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30"></textarea>
                        @error('instructions') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </label>
                    <label class="space-y-2 text-sm font-semibold text-slate-600">
                        <span>Nota minima (%)</span>
                        <input type="number" min="1" max="100" wire:model.defer="passing_score"
                            class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30">
                        @error('passing_score') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </label>
                    <label class="space-y-2 text-sm font-semibold text-slate-600">
                        <span>Tentativas maximas</span>
                        <input type="number" min="1" max="10" wire:model.defer="max_attempts"
                            class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30">
                        @error('max_attempts') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </label>
                    <label class="space-y-2 text-sm font-semibold text-slate-600 md:col-span-2">
                        <span>Duracao (minutos)</span>
                        <input type="number" min="5" wire:model.defer="duration_minutes"
                            class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30">
                        @error('duration_minutes') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </label>
                    <div class="md:col-span-2 flex flex-wrap gap-3">
                        <button type="submit" class="edux-btn" wire:loading.attr="disabled">
                            {{ $finalTest ? 'Salvar teste' : 'Criar teste final' }}
                        </button>
                        <button type="button" class="edux-btn bg-white text-edux-primary"
                            wire:click="closeForm">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if ($confirmingDelete)
        <div class="fixed inset-0 z-40 flex items-center justify-center bg-black/70 px-4">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-card space-y-4">
                <h3 class="text-xl font-display text-edux-primary">Remover teste?</h3>
                <p class="text-sm text-slate-600">
                    Todas as questoes e alternativas vinculadas serao excluidas. Esta acao nao pode ser desfeita.
                </p>
                <div class="flex flex-wrap gap-3">
                    <button type="button" class="edux-btn bg-red-500 text-white" wire:click="deleteFinalTest"
                        wire:loading.attr="disabled">
                        Excluir teste
                    </button>
                    <button type="button" class="edux-btn bg-white text-edux-primary"
                        wire:click="$set('confirmingDelete', false)">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    @endif
</section>
