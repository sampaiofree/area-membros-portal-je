<section class="space-y-6 rounded-card bg-white p-6 shadow-card" x-data="{ frontPreviewOpen: false, backPreviewOpen: false }">
    <header class="space-y-2">
        <p class="text-sm uppercase tracking-wide text-edux-primary">Certificados</p>
        <h1 class="font-display text-3xl text-edux-primary">Modelos padrão</h1>
        <p class="text-slate-600">Estas imagens serão usadas em todos os cursos que não definirem um fundo próprio.</p>
    </header>

    <form wire:submit.prevent="save" class="grid gap-6 md:grid-cols-2">
        <div class="space-y-3">
            <label class="text-sm font-semibold text-slate-600">Imagem da frente</label>
            <input type="file" wire:model="front_background" accept="image/*" class="w-full rounded-xl border border-edux-line px-4 py-3 text-sm">
            @error('front_background') <span class="text-xs text-red-500">{{ $message }}</span> @enderror

            <div class="rounded-2xl border border-edux-line bg-edux-background/40 p-3 space-y-2">
                @if ($front_background)
                    <img src="{{ $front_background->temporaryUrl() }}" alt="Prévia da frente" class="w-full rounded-xl border border-edux-line object-cover">
                    <p class="text-xs text-slate-500">Pré-visualização temporária</p>
                @elseif ($branding->front_background_path)
                    <div class="flex items-center justify-between text-xs font-semibold text-edux-primary">
                        <span>Pré-visualização atual</span>
                        <div class="flex gap-2">
                            <button type="button" class="underline-offset-2 hover:underline" @click="frontPreviewOpen = true">Ver</button>
                            <button type="button" class="text-red-500 underline-offset-2 hover:underline" wire:click="deleteFront">Remover</button>
                        </div>
                    </div>
                    <img src="{{ $branding->front_background_url }}" alt="Frente atual" class="w-full rounded-xl border border-edux-line object-cover">
                @else
                    <p class="text-xs text-slate-500">Nenhuma imagem cadastrada.</p>
                @endif
            </div>
        </div>

        <div class="space-y-3">
            <label class="text-sm font-semibold text-slate-600">Imagem do verso</label>
            <input type="file" wire:model="back_background" accept="image/*" class="w-full rounded-xl border border-edux-line px-4 py-3 text-sm">
            @error('back_background') <span class="text-xs text-red-500">{{ $message }}</span> @enderror

            <div class="rounded-2xl border border-edux-line bg-edux-background/40 p-3 space-y-2">
                @if ($back_background)
                    <img src="{{ $back_background->temporaryUrl() }}" alt="Prévia do verso" class="w-full rounded-xl border border-edux-line object-cover">
                    <p class="text-xs text-slate-500">Pré-visualização temporária</p>
                @elseif ($branding->back_background_path)
                    <div class="flex items-center justify-between text-xs font-semibold text-edux-primary">
                        <span>Pré-visualização atual</span>
                        <div class="flex gap-2">
                            <button type="button" class="underline-offset-2 hover:underline" @click="backPreviewOpen = true">Ver</button>
                            <button type="button" class="text-red-500 underline-offset-2 hover:underline" wire:click="deleteBack">Remover</button>
                        </div>
                    </div>
                    <img src="{{ $branding->back_background_url }}" alt="Verso atual" class="w-full rounded-xl border border-edux-line object-cover">
                @else
                    <p class="text-xs text-slate-500">Nenhuma imagem cadastrada.</p>
                @endif
            </div>
        </div>

        <div class="md:col-span-2 flex flex-wrap gap-3">
            <button type="submit" class="edux-btn">Salvar imagens</button>
            <p class="text-xs text-slate-500">Formatos aceitos: JPG, PNG ou WEBP · até 4MB.</p>
        </div>
    </form>

    @if ($branding->front_background_url)
    <template x-if="frontPreviewOpen">
        <div class="fixed inset-0 z-30 flex items-center justify-center bg-black/70 p-4" @click.self="frontPreviewOpen = false">
            <div class="max-w-3xl rounded-3xl bg-white p-4 shadow-card">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-display text-edux-primary">Frente do certificado</h2>
                    <button type="button" @click="frontPreviewOpen = false" class="text-sm font-semibold text-edux-primary">Fechar</button>
                </div>
                <img src="{{ $branding->front_background_url }}" alt="Frente do certificado" class="mt-4 w-full rounded-2xl border border-edux-line">
            </div>
        </div>
    </template>
    @endif

    @if ($branding->back_background_url)
    <template x-if="backPreviewOpen">
        <div class="fixed inset-0 z-30 flex items-center justify-center bg-black/70 p-4" @click.self="backPreviewOpen = false">
            <div class="max-w-3xl rounded-3xl bg-white p-4 shadow-card">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-display text-edux-primary">Verso do certificado</h2>
                    <button type="button" @click="backPreviewOpen = false" class="text-sm font-semibold text-edux-primary">Fechar</button>
                </div>
                <img src="{{ $branding->back_background_url }}" alt="Verso do certificado" class="mt-4 w-full rounded-2xl border border-edux-line">
            </div>
        </div>
    </template>
    @endif
</section>
