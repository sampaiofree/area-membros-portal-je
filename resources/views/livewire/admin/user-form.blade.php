<form wire:submit.prevent="save" class="rounded-card bg-white p-6 shadow-card space-y-5">
    <div class="grid gap-4 md:grid-cols-2">
        <label class="space-y-2 text-sm font-semibold text-slate-600">
            <span>Nome completo</span>
            <input
                type="text"
                wire:model.defer="name"
                required
                class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30"
            >
            @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </label>
        <label class="space-y-2 text-sm font-semibold text-slate-600">
            <span>E-mail</span>
            <input
                type="email"
                wire:model.defer="email"
                required
                class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30"
            >
            @error('email') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </label>
        <label class="space-y-2 text-sm font-semibold text-slate-600">
            <span>WhatsApp</span>
            <input
                type="text"
                wire:model.defer="whatsapp"
                class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30"
                placeholder="(11) 99999-9999"
            >
            @error('whatsapp') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </label>
        <label class="space-y-2 text-sm font-semibold text-slate-600">
            <span>Foto de perfil</span>
            <input
                type="file"
                wire:model="profilePhoto"
                accept="image/*"
                class="w-full rounded-xl border border-dashed border-edux-line px-4 py-3 file:mr-3 file:rounded-lg file:border-none file:bg-edux-primary file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white"
            >
            @error('profilePhoto') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </label>
    </div>

    <label class="space-y-2 text-sm font-semibold text-slate-600 block">
        <span>Perfil de acesso</span>
        <div class="grid gap-3 sm:grid-cols-3">
            @foreach ($roles as $option)
                <label class="flex items-center gap-2 rounded-2xl border border-edux-line/60 px-4 py-3 text-sm font-semibold text-slate-600">
                    <input
                        type="radio"
                        wire:model="role"
                        value="{{ $option->value }}"
                        class="text-edux-primary focus:ring-edux-primary"
                    >
                    <span>{{ $option->label() }}</span>
                </label>
            @endforeach
        </div>
        @error('role') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
    </label>

    <label class="space-y-2 text-sm font-semibold text-slate-600 block">
        <span>Qualificações, bio ou nota para o perfil</span>
        <textarea
            rows="4"
            wire:model.defer="qualification"
            class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30"
        ></textarea>
        @error('qualification') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
    </label>

    <div class="grid gap-4 md:grid-cols-2">
        <label class="space-y-2 text-sm font-semibold text-slate-600">
            <span>Senha temporária</span>
            <input
                type="password"
                wire:model.defer="password"
                required
                class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30"
            >
            @error('password') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </label>
        <label class="space-y-2 text-sm font-semibold text-slate-600">
            <span>Confirmar senha</span>
            <input
                type="password"
                wire:model.defer="password_confirmation"
                required
                class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30"
            >
        </label>
    </div>

    <p class="text-xs text-slate-500">Envie as credenciais para o usuário e peça para atualizar a senha após o primeiro acesso.</p>

    <div class="flex flex-wrap gap-3">
        <button type="submit" class="edux-btn">Criar usuário</button>
    </div>
</form>
