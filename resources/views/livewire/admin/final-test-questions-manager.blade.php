<section class="rounded-card bg-white p-6 shadow-card space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <p class="text-sm uppercase tracking-wide text-edux-primary">Questões</p>
            <h3 class="text-xl font-display text-edux-primary">{{ $questions->count() }} cadastradas</h3>
        </div>
        <button type="button" class="edux-btn" wire:click="showCreateForm">
            {{ $editingQuestion ? 'Editar questão' : 'Nova questão' }}
        </button>
    </div>

    <div class="space-y-4">
        @forelse ($questions as $question)
            <article class="rounded-2xl border border-edux-line/70 p-4" wire:key="question-{{ $question->id }}" x-data="{ openQuestion: false }">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div class="flex items-start gap-3">
                        <div class="flex flex-col text-sm text-slate-500">
                            <button type="button" wire:click.prevent="moveQuestion({{ $question->id }}, 'up')" class="rounded-full bg-edux-background px-2 py-1 hover:text-edux-primary" aria-label="Mover questão para cima">&uarr;</button>
                            <button type="button" wire:click.prevent="moveQuestion({{ $question->id }}, 'down')" class="mt-1 rounded-full bg-edux-background px-2 py-1 hover:text-edux-primary" aria-label="Mover questão para baixo">&darr;</button>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-slate-500">Posição {{ $question->position }} · Peso {{ $question->weight }}</p>
                            <h4 class="text-lg font-display text-edux-primary">{{ $question->title }}</h4>
                            <p class="text-sm text-slate-600">{{ $question->statement ? Str::limit(strip_tags($question->statement), 140) : 'Sem enunciado adicional.' }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-2 text-sm font-semibold text-edux-primary">
                        <button type="button" wire:click="startEdit({{ $question->id }})" class="underline-offset-2 hover:underline text-left">Editar</button>
                        <button type="button" onclick="if(!confirm('Remover questão?')) return;" wire:click="deleteQuestion({{ $question->id }})" class="text-red-500 underline-offset-2 hover:underline text-left">Excluir</button>
                        <button type="button" class="underline-offset-2 hover:underline text-left" @click="openQuestion = !openQuestion">
                            <span x-text="openQuestion ? 'Fechar opções' : 'Gerenciar opções'"></span>
                        </button>
                    </div>
                </div>
                <div class="mt-4" x-show="openQuestion" x-collapse>
                    <livewire:admin.final-test-question-options-manager :question-id="$question->id" :key="'question-options-'.$question->id" />
                </div>
            </article>
        @empty
            <p class="text-sm text-slate-500">Adicione questões de múltipla escolha para liberar o teste.</p>
        @endforelse
    </div>

    @if ($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">
            <div class="absolute inset-0" @click="$wire.cancelEdit()"></div>
            <div class="relative z-10 w-full max-w-4xl rounded-3xl bg-white p-6 shadow-2xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-edux-primary">Formulário</p>
                        <h3 class="text-2xl font-display text-edux-primary">{{ $editingQuestion ? 'Editar questão' : 'Nova questão' }}</h3>
                    </div>
                    <button type="button" class="text-sm font-semibold text-slate-500 hover:text-edux-primary" wire:click="cancelEdit">Fechar</button>
                </div>
                <form wire:submit.prevent="{{ $editingQuestion ? 'updateQuestion' : 'createQuestion' }}" class="mt-6 grid gap-4 md:grid-cols-2">
                    <label class="space-y-2 text-sm font-semibold text-slate-600 md:col-span-2">
                        <span>Título</span>
                        <input type="text" wire:model.defer="title" required class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30">
                        @error('title') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </label>
                    <label class="space-y-2 text-sm font-semibold text-slate-600 md:col-span-2">
                        <span>Enunciado</span>
                        <textarea wire:model.defer="statement" rows="3" class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30"></textarea>
                        @error('statement') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </label>
                    <label class="space-y-2 text-sm font-semibold text-slate-600">
                        <span>Posição</span>
                        <input type="number" min="1" wire:model.defer="position" class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30">
                        @error('position') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </label>
                    <label class="space-y-2 text-sm font-semibold text-slate-600">
                        <span>Peso</span>
                        <input type="number" min="1" max="10" wire:model.defer="weight" class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30">
                        @error('weight') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </label>
                    <div class="md:col-span-2 flex flex-wrap gap-3">
                        <button type="submit" class="edux-btn">
                            {{ $editingQuestion ? 'Salvar questão' : 'Adicionar questão' }}
                        </button>
                        <button type="button" class="edux-btn bg-white text-edux-primary" wire:click="cancelEdit">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</section>
