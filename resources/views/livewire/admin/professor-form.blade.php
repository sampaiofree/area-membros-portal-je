<form wire:submit.prevent="save" class="rounded-card bg-white p-6 shadow-card space-y-4">
    <label class="space-y-2 text-sm font-semibold text-slate-600">
        <span>Nome completo</span>
        <input type="text" wire:model.defer="name" required class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30">
        @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
    </label>

    <label class="space-y-2 text-sm font-semibold text-slate-600">
        <span>E-mail</span>
        <input type="email" wire:model.defer="email" required class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30">
        @error('email') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
    </label>

    <label class="space-y-2 text-sm font-semibold text-slate-600">
        <span>Senha</span>
        <input type="password" wire:model.defer="password" required class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30">
        @error('password') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
    </label>

    <label class="space-y-2 text-sm font-semibold text-slate-600">
        <span>Confirmar senha</span>
        <input type="password" wire:model.defer="password_confirmation" required class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30">
    </label>

    <button type="submit" class="edux-btn w-full md:w-auto">Cadastrar professor</button>
</form>
