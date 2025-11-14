@extends('layouts.app')

@section('title', 'Usuários')

@section('content')
    <section class="space-y-6">
        <header class="rounded-card bg-white p-6 shadow-card flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-sm uppercase tracking-wide text-edux-primary">Equipe e alunos</p>
                <h1 class="font-display text-3xl text-edux-primary">Usuários cadastrados</h1>
                <p class="text-slate-600 text-sm">Pesquise, visualize e edite qualquer perfil da plataforma.</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="edux-btn">
                Novo usuário
            </a>
        </header>

        <div class="rounded-card bg-white p-6 shadow-card space-y-4">
            <form method="GET" class="flex flex-col gap-3 md:flex-row">
                <label class="flex-1 text-sm font-semibold text-slate-600">
                    <span class="sr-only">Buscar</span>
                    <input
                        type="search"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Nome, e-mail ou WhatsApp"
                        class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30"
                    >
                </label>
                <button type="submit" class="edux-btn w-full md:w-auto">Buscar</button>
            </form>

            <div class="overflow-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-slate-500 text-xs uppercase tracking-wide">
                            <th class="pb-2">Nome</th>
                            <th class="pb-2">E-mail</th>
                            <th class="pb-2">Papel</th>
                            <th class="pb-2">WhatsApp</th>
                            <th class="pb-2 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($users as $user)
                            <tr>
                                <td class="py-3">
                                    <div class="font-semibold text-edux-primary">{{ $user->preferredName() }}</div>
                                    <p class="text-xs text-slate-500">ID #{{ $user->id }}</p>
                                </td>
                                <td class="py-3">{{ $user->email }}</td>
                                <td class="py-3">{{ $user->role->label() }}</td>
                                <td class="py-3">{{ $user->whatsapp ?? '—' }}</td>
                                <td class="py-3 text-right">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-edux-primary text-sm underline-offset-2 hover:underline">Editar</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-sm text-slate-500">
                                    Nenhum usuário encontrado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $users->links() }}
        </div>
    </section>
@endsection
