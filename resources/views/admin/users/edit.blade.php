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

            <div class="rounded-2xl border border-edux-line/70 p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-display text-edux-primary">Saldo de DUX</h3>
                    <p class="text-sm text-slate-600">Atual: <strong class="text-edux-primary">{{ $duxBalance }} DUX</strong></p>
                </div>
                <div class="grid gap-3 md:grid-cols-3">
                    <label class="space-y-1 text-sm font-semibold text-slate-600">
                        <span>Valor</span>
                        <input
                            type="number"
                            name="dux_amount"
                            min="1"
                            class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30"
                            placeholder="Ex.: 50"
                        >
                    </label>
                    <label class="space-y-1 text-sm font-semibold text-slate-600">
                        <span>Ação</span>
                        <select name="dux_action" class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30">
                            <option value="">Selecionar</option>
                            <option value="add">Adicionar</option>
                            <option value="remove">Remover</option>
                        </select>
                    </label>
                    <label class="space-y-1 text-sm font-semibold text-slate-600">
                        <span>Observação (opcional)</span>
                        <input
                            type="text"
                            name="dux_reason"
                            class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30"
                            placeholder="Motivo do ajuste"
                        >
                    </label>
                </div>
                @error('dux_amount') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                @error('dux_action') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                @error('dux_reason') <span class="text-xs text-red-500">{{ $message }}</span> @enderror

                @if (isset($recentTransactions) && $recentTransactions->count())
                    <div class="space-y-2">
                        <p class="text-xs font-semibold uppercase text-slate-500">Últimos ajustes</p>
                        <ul class="divide-y divide-edux-line/60 rounded-xl border border-edux-line/60 bg-edux-background">
                            @foreach ($recentTransactions as $txn)
                                <li class="flex items-center justify-between px-4 py-2 text-sm">
                                    <span class="text-slate-600">{{ optional($txn->created_at)->format('d/m H:i') ?? '--' }}</span>
                                    <span class="font-semibold {{ $txn->amount >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                        {{ $txn->amount >= 0 ? '+' : '' }}{{ $txn->amount }} DUX
                                    </span>
                                    <span class="text-slate-500">
                                        {{ $txn->reason ?? $txn->source ?? 'ajuste' }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <div class="flex flex-wrap gap-3">
                <button type="submit" class="edux-btn">Salvar alterações</button>
                <a href="{{ route('admin.users.index') }}" class="edux-btn bg-white text-edux-primary">Cancelar</a>
            </div>
        </form>
    </section>
@endsection
