<section class="space-y-6">
    <div class="rounded-card bg-white p-6 shadow-card space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm uppercase tracking-wide text-edux-primary">Comunicação</p>
                <h1 class="text-2xl font-display text-edux-primary">{{ $editing ? 'Editar notificação' : 'Nova notificação' }}</h1>
            </div>
            @if ($editing)
                <button type="button" wire:click="cancelEdit" class="edux-btn bg-white text-edux-primary">Cancelar edição</button>
            @endif
        </div>

        <form wire:submit.prevent="save" class="grid gap-4 md:grid-cols-2">
            <label class="space-y-1 text-sm font-semibold text-slate-600">
                <span>Título</span>
                <input type="text" wire:model.defer="title" class="w-full rounded-xl border border-edux-line px-4 py-3">
                @error('title') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </label>
            <label class="space-y-1 text-sm font-semibold text-slate-600">
                <span>Publicar em</span>
                <input type="datetime-local" wire:model.defer="published_at" class="w-full rounded-xl border border-edux-line px-4 py-3">
            </label>

            <label class="md:col-span-2 space-y-1 text-sm font-semibold text-slate-600">
                <span>Mensagem</span>
                <textarea wire:model.defer="body" rows="4" class="w-full rounded-xl border border-edux-line px-4 py-3"></textarea>
            </label>

            <label class="space-y-1 text-sm font-semibold text-slate-600">
                <span>Imagem</span>
                <input type="file" wire:model="image" accept="image/*" class="w-full rounded-xl border border-edux-line px-4 py-3">
                @error('image') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                @if ($image)
                    <img src="{{ $image->temporaryUrl() }}" class="mt-2 rounded-xl border border-edux-line object-cover">
                @elseif ($editing?->image_path)
                    <img src="{{ asset('storage/'.$editing->image_path) }}" class="mt-2 rounded-xl border border-edux-line object-cover">
                @endif
            </label>

            <label class="space-y-1 text-sm font-semibold text-slate-600">
                <span>Vídeo (URL)</span>
                <input type="url" wire:model.defer="video_url" class="w-full rounded-xl border border-edux-line px-4 py-3">
            </label>

            <label class="space-y-1 text-sm font-semibold text-slate-600">
                <span>Texto do botão</span>
                <input type="text" wire:model.defer="button_label" class="w-full rounded-xl border border-edux-line px-4 py-3">
            </label>
            <label class="space-y-1 text-sm font-semibold text-slate-600">
                <span>Link do botão</span>
                <input type="url" wire:model.defer="button_url" class="w-full rounded-xl border border-edux-line px-4 py-3">
            </label>

            <div class="md:col-span-2">
                <button type="submit" class="edux-btn">Salvar notificação</button>
            </div>
        </form>
    </div>

    <div class="rounded-card bg-white p-6 shadow-card space-y-4">
        <h2 class="text-xl font-display text-edux-primary">Notificações publicadas</h2>
        <div class="space-y-3">
            @forelse ($notifications as $notification)
                <article class="rounded-2xl border border-edux-line/70 p-4 flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="text-sm text-slate-500">{{ optional($notification->published_at)->format('d/m/Y H:i') ?? 'Rascunho' }}</p>
                        <h3 class="text-lg font-semibold text-edux-primary">{{ $notification->title }}</h3>
                    </div>
                    <div class="flex gap-2 text-sm font-semibold">
                        <button type="button" wire:click="edit({{ $notification->id }})" class="edux-btn bg-white text-edux-primary">Editar</button>
                        <button type="button" onclick="if(!confirm('Remover notificação?')) return;" wire:click="delete({{ $notification->id }})" class="edux-btn bg-red-500 text-white">Excluir</button>
                    </div>
                </article>
            @empty
                <p class="text-sm text-slate-500">Nenhuma notificação cadastrada.</p>
            @endforelse
        </div>

        {{ $notifications->links() }}
    </div>
</section>
