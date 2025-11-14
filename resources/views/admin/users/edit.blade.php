@extends('layouts.app')

@section('title', 'Editar usuário')

@section('content')
    <section class="space-y-6">
        <header class="rounded-card bg-white p-6 shadow-card flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-sm uppercase tracking-wide text-edux-primary">Usuário</p>
                <h1 class="font-display text-3xl text-edux-primary">{{ $user->preferredName() }}</h1>
                <p class="text-slate-600 text-sm">Atualize dados, redefina senha e ajuste permissões.</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="edux-btn bg-white text-edux-primary">
                Voltar para a lista
            </a>
        </header>

        <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data" class="rounded-card bg-white p-6 shadow-card space-y-5">
            @csrf
            @method('PUT')

            <div class="grid gap-4 md:grid-cols-2">
                <label class="space-y-2 text-sm font-semibold text-slate-600">
                    <span>Nome completo</span>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        required
                        class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30"
                    >
                    @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </label>
                <label class="space-y-2 text-sm font-semibold text-slate-600">
                    <span>E-mail</span>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        required
                        class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30"
                    >
                    @error('email') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </label>
                <label class="space-y-2 text-sm font-semibold text-slate-600">
                    <span>WhatsApp</span>
                    <input
                        type="text"
                        name="whatsapp"
                        value="{{ old('whatsapp', $user->whatsapp) }}"
                        class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30"
                    >
                    @error('whatsapp') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </label>
                <label class="space-y-2 text-sm font-semibold text-slate-600">
                    <span>Foto de perfil</span>
                    <input
                        type="file"
                        name="profile_photo"
                        accept="image/*"
                        class="w-full rounded-xl border border-dashed border-edux-line px-4 py-3 file:mr-3 file:rounded-lg file:border-none file:bg-edux-primary file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white"
                    >
                    @error('profile_photo') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    @if ($user->profilePhotoUrl())
                        <div class="flex items-center gap-3 text-xs text-slate-500 pt-2">
                            <img src="{{ $user->profilePhotoUrl() }}" alt="Foto atual" class="h-10 w-10 rounded-full object-cover">
                            <label class="inline-flex items-center gap-2">
                                <input type="checkbox" name="remove_photo" value="1" class="text-edux-primary focus:ring-edux-primary">
                                <span>Remover foto atual</span>
                            </label>
                        </div>
                    @endif
                </label>
            </div>

            <div class="space-y-2 text-sm font-semibold text-slate-600">
                <span>Perfil de acesso</span>
                <div class="grid gap-3 sm:grid-cols-3">
                    @foreach ($roles as $option)
                        <label class="flex items-center gap-2 rounded-2xl border border-edux-line/60 px-4 py-3 text-sm font-semibold text-slate-600">
                            <input
                                type="radio"
                                name="role"
                                value="{{ $option->value }}"
                                @checked(old('role', $user->role->value) === $option->value)
                                class="text-edux-primary focus:ring-edux-primary"
                            >
                            <span>{{ $option->label() }}</span>
                        </label>
                    @endforeach
                </div>
                @error('role') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <label class="space-y-2 text-sm font-semibold text-slate-600 block">
                <span>Qualificações / bio</span>
                <textarea
                    rows="4"
                    name="qualification"
                    class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30"
                >{{ old('qualification', $user->qualification) }}</textarea>
                @error('qualification') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </label>

            <div class="grid gap-4 md:grid-cols-2">
                <label class="space-y-2 text-sm font-semibold text-slate-600">
                    <span>Nova senha (opcional)</span>
                    <input
                        type="password"
                        name="password"
                        class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30"
                    >
                    @error('password') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </label>
                <label class="space-y-2 text-sm font-semibold text-slate-600">
                    <span>Confirmar nova senha</span>
                    <input
                        type="password"
                        name="password_confirmation"
                        class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30"
                    >
                </label>
            </div>

            <div class="flex flex-wrap gap-3">
                <button type="submit" class="edux-btn">Salvar alterações</button>
                <a href="{{ route('admin.users.index') }}" class="edux-btn bg-white text-edux-primary">Cancelar</a>
            </div>
        </form>
    </section>
@endsection
