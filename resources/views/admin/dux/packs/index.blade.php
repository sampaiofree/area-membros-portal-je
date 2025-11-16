@extends('layouts.app')

@section('title', 'Pacotes de Duxes')

@section('content')
    <div class="space-y-6">
        <header class="rounded-card bg-white p-5 shadow-card">
            <h1 class="font-display text-2xl text-edux-primary">Pacotes de duxes</h1>
            <p class="text-sm text-slate-600">Configure os pacotes que os alunos podem comprar.</p>
        </header>

        <div class="rounded-card bg-white p-4 shadow-card">
            <form method="POST" action="{{ route('admin.dux.packs.store') }}" class="grid gap-3 md:grid-cols-2">
                @csrf
                <label class="text-sm font-semibold text-slate-600">Nome
                    <input type="text" name="name" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2" required>
                </label>
                <label class="text-sm font-semibold text-slate-600">Duxes
                    <input type="number" name="duxes" min="1" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2" required>
                </label>
                <label class="text-sm font-semibold text-slate-600">Preco (centavos)
                    <input type="number" name="price_cents" min="0" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2" required>
                </label>
                <label class="text-sm font-semibold text-slate-600">Moeda
                    <input type="text" name="currency" value="BRL" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2">
                </label>
                <div class="flex items-center gap-2 pt-6">
                    <input type="checkbox" name="active" value="1" class="h-4 w-4 rounded border-slate-300">
                    <span class="text-sm font-semibold text-slate-600">Ativo</span>
                </div>
                <div class="md:col-span-2 flex justify-end">
                    <button type="submit" class="edux-btn">Adicionar pacote</button>
                </div>
            </form>
        </div>

        <div class="rounded-card bg-white p-4 shadow-card">
            <table class="w-full text-sm text-slate-700">
                <thead>
                    <tr class="text-left text-xs uppercase text-slate-500">
                        <th class="py-2">Nome</th>
                        <th>Duxes</th>
                        <th>Preco</th>
                        <th>Moeda</th>
                        <th>Ativo</th>
                        <th class="text-right">Acoes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($packs as $pack)
                        <tr class="border-t">
                            <td class="py-2 font-semibold">{{ $pack->name }}</td>
                            <td>{{ $pack->duxes }}</td>
                            <td>R$ {{ number_format($pack->price_cents / 100, 2, ',', '.') }}</td>
                            <td>{{ $pack->currency }}</td>
                            <td>{{ $pack->active ? 'Sim' : 'Nao' }}</td>
                            <td class="text-right">
                                <form method="POST" action="{{ route('admin.dux.packs.update', $pack) }}" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="name" value="{{ $pack->name }}">
                                    <input type="hidden" name="duxes" value="{{ $pack->duxes }}">
                                    <input type="hidden" name="price_cents" value="{{ $pack->price_cents }}">
                                    <input type="hidden" name="currency" value="{{ $pack->currency }}">
                                    <input type="hidden" name="active" value="{{ $pack->active ? 1 : 0 }}">
                                    <button type="submit" class="text-blue-600 text-xs">Salvar</button>
                                </form>
                                <form method="POST" action="{{ route('admin.dux.packs.destroy', $pack) }}" class="inline" onsubmit="return confirm('Remover pacote?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 text-xs ml-2">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center text-slate-500">Nenhum pacote cadastrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
