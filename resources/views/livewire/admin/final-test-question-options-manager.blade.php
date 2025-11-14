<div class="rounded-2xl border border-edux-line/70 bg-edux-background p-4 space-y-4">
    <div class="flex items-center justify-between gap-2">
        <h5 class="text-sm font-semibold text-edux-primary">{{ $question->options->count() }} opções</h5>
        <button type="button" class="text-xs font-semibold text-edux-primary underline-offset-2 hover:underline" wire:click="showCreateForm">
            {{ $editingOption ? 'Editar alternativa' : 'Nova alternativa' }}
        </button>
    </div>

    <ul class="space-y-2">
        @forelse ($question->options->sortBy('position') as $option)
            <li class="flex flex-wrap items-center justify-between gap-2 rounded-xl bg-white px-3 py-2 text-sm shadow-sm" wire:key="option-{{ $option->id }}">
                <div class="flex items-center gap-3">
                    <div class="flex flex-col text-xs text-slate-500">
                        <button type="button" wire:click.prevent="moveOption({{ $option->id }}, 'up')" class="rounded-full bg-edux-background px-2 py-1 hover:text-edux-primary" aria-label="Mover opção para cima">
                            &uarr;
                        </button>
                        <button type="button" wire:click.prevent="moveOption({{ $option->id }}, 'down')" class="mt-1 rounded-full bg-edux-background px-2 py-1 hover:text-edux-primary" aria-label="Mover opção para baixo">
                            &darr;
                        </button>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-800">{{ $option->label }}</p>
                        <p class="text-xs text-slate-500">Posição {{ $option->position }}</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2 text-xs font-semibold">
                    @if ($option->is_correct)
                        <span class="rounded-full bg-green-100 px-2 py-0.5 text-green-700">Correta</span>
                    @endif
                    <button type="button" wire:click="startEdit({{ $option->id }})" class="text-edux-primary underline-offset-2 hover:underline">Editar</button>
                    <button type="button" onclick="if(!confirm('Remover alternativa?')) return;" wire:click="deleteOption({{ $option->id }})" class="text-red-500 underline-offset-2 hover:underline">
                        Excluir
                    </button>
                </div>
            </li>
        @empty
            <li class="text-xs text-slate-500">Cadastre pelo menos duas alternativas.</li>
        @endforelse
    </ul>

    @if ($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">
            <div class="absolute inset-0" @click="$wire.cancelEdit()"></div>
            <div class="relative z-10 w-full max-w-2xl rounded-3xl bg-white p-6 shadow-2xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-edux-primary">Alternativas</p>
                        <h3 class="text-2xl font-display text-edux-primary">
                            {{ $editingOption ? 'Editar alternativa' : 'Adicionar alternativa' }}
                        </h3>
                    </div>
                    <button type="button" class="text-sm font-semibold text-slate-500 hover:text-edux-primary" wire:click="cancelEdit">
                        Fechar
                    </button>
                </div>
                <form wire:submit.prevent="{{ $editingOption ? 'updateOption' : 'createOption' }}" class="mt-6 grid gap-3 md:grid-cols-2">
                    <label class="space-y-1 text-sm font-semibold text-slate-600 md:col-span-2">
                        <span>Texto da alternativa</span>
                        <input type="text" wire:model.defer="label" required class="w-full rounded-xl border border-edux-line px-3 py-2 focus:border-edux-primary focus:ring-edux-primary/30">
                        @error('label') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </label>
                    <label class="space-y-1 text-sm font-semibold text-slate-600">
                        <span>Posição</span>
                        <input type="number" min="1" wire:model.defer="position" class="w-full rounded-xl border border-edux-line px-3 py-2 focus:border-edux-primary focus:ring-edux-primary/30">
                        @error('position') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </label>
                    <label class="flex items-center gap-2 text-sm font-semibold text-slate-600">
                        <input type="checkbox" wire:model.defer="is_correct" class="h-4 w-4 rounded border-edux-line text-edux-primary focus:ring-edux-primary/50">
                        <span>Esta é a alternativa correta</span>
                    </label>
                    <div class="md:col-span-2 flex flex-wrap gap-3">
                        <button type="submit" class="edux-btn text-sm">
                            {{ $editingOption ? 'Salvar alternativa' : 'Adicionar alternativa' }}
                        </button>
                        <button type="button" class="edux-btn bg-white text-edux-primary text-sm" wire:click="cancelEdit">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
