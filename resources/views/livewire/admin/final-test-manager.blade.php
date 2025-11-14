<section class="rounded-card bg-white p-6 shadow-card space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <p class="text-sm uppercase tracking-wide text-edux-primary">Avaliação</p>
            <h2 class="text-2xl font-display text-edux-primary">Teste final</h2>
            <p class="text-sm text-slate-600">Configure título, tentativas e duração do teste.</p>
        </div>
        <div class="flex items-center gap-3">
            @if ($finalTest)
                <span class="rounded-full bg-edux-background px-4 py-1 text-sm font-semibold text-edux-primary">
                    {{ $finalTest->questions->count() }} questões
                </span>
            @endif
            <button type="button" class="edux-btn" wire:click="openForm">
                {{ $finalTest ? 'Editar teste final' : 'Configurar teste final' }}
            </button>
        </div>
    </div>

    @if (! $finalTest)
        <p class="text-sm text-slate-500">Nenhum teste final configurado ainda. Crie um para liberar o certificado.</p>
    @else
        <div class="rounded-2xl border border-edux-line/70 bg-edux-background p-4 md:flex md:items-center md:justify-between">
            <div>
                <p class="font-semibold text-edux-primary">{{ $finalTest->title }}</p>
                <p class="text-sm text-slate-600">
                    Nota mínima {{ $finalTest->passing_score }}% · {{ $finalTest->max_attempts }} tentativa(s) ·
                    {{ $finalTest->duration_minutes ?? 'Sem limite' }} min
                </p>
            </div>
            <div class="mt-3 flex items-center gap-2 md:mt-0">
                <span class="text-xs text-slate-500">Atualizado {{ $finalTest->updated_at->diffForHumans() }}</span>
                <button type="button" class="text-xs font-semibold text-red-500 underline-offset-2 hover:underline"
                    wire:click="$set('confirmingDelete', true)">
                    Remover teste
                </button>
            </div>
        </div>

        <livewire:admin.final-test-questions-manager :final-test-id="$finalTest->id" :key="'final-test-questions-'.$finalTest->id" />
    @endif

    @if ($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">
            <div class="absolute inset-0" @click="$set('showForm', false)"></div>
            <div class="relative z-10 w-full max-w-4xl rounded-3xl bg-white p-6 shadow-2xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-edux-primary">Configuração</p>
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
                        <span>Título do teste</span>
                        <input type="text" wire:model.defer="title" required
                            class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30">
                        @error('title') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </label>
                    <label class="space-y-2 text-sm font-semibold text-slate-600 md:col-span-2">
                        <span>Instruções para os alunos</span>
                        <textarea wire:model.defer="instructions" rows="3"
                            class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30"></textarea>
                        @error('instructions') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </label>
                    <label class="space-y-2 text-sm font-semibold text-slate-600">
                        <span>Nota mínima (%)</span>
                        <input type="number" min="1" max="100" wire:model.defer="passing_score"
                            class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30">
                        @error('passing_score') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </label>
                    <label class="space-y-2 text-sm font-semibold text-slate-600">
                        <span>Tentativas máximas</span>
                        <input type="number" min="1" max="10" wire:model.defer="max_attempts"
                            class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30">
                        @error('max_attempts') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </label>
                    <label class="space-y-2 text-sm font-semibold text-slate-600 md:col-span-2">
                        <span>Duração (minutos)</span>
                        <input type="number" min="5" wire:model.defer="duration_minutes"
                            class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30">
                        @error('duration_minutes') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </label>
                    <div class="md:col-span-2 flex flex-wrap gap-3">
                        <button type="submit" class="edux-btn">
                            {{ $finalTest ? 'Salvar alterações' : 'Criar teste final' }}
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
                <p class="text-sm text-slate-600">Todas as questões e tentativas relacionadas serão excluídas. Esta ação não pode ser desfeita.</p>
                <div class="flex flex-wrap gap-3">
                    <button type="button" class="edux-btn bg-red-500 text-white" wire:click="deleteFinalTest">
                        Excluir teste
                    </button>
                    <button type="button" class="edux-btn bg-white text-edux-primary" wire:click="$set('confirmingDelete', false)">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    @endif
</section>
