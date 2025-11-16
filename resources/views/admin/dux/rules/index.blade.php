@extends('layouts.app')

@section('title', 'Regras de Duxes')

@section('content')
    <div class="space-y-6">
        <header class="rounded-card bg-white p-5 shadow-card">
            <h1 class="font-display text-2xl text-edux-primary">Regras de duxes</h1>
            <p class="text-sm text-slate-600">Configure quanto ganha ou gasta em cada acao.</p>
        </header>

        <div class="rounded-card bg-white p-4 shadow-card">
            <table class="w-full text-sm text-slate-700">
                <thead>
                    <tr class="text-left text-xs uppercase text-slate-500">
                        <th class="py-2">Nome</th>
                        <th>Slug</th>
                        <th>Direcao</th>
                        <th>Valor</th>
                        <th>Modelo</th>
                        <th class="text-right">Acoes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rules as $rule)
                        <tr class="border-t">
                            <td class="py-2 font-semibold">{{ $rule->name }}</td>
                            <td>{{ $rule->slug }}</td>
                            <td>{{ $rule->direction }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.dux.rules.update', $rule) }}" class="flex items-center gap-2 justify-start">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="amount" value="{{ $rule->amount }}" min="1" class="w-24 rounded border border-slate-300 px-2 py-1">
                                    <button type="submit" class="text-blue-600 text-xs font-semibold">Salvar</button>
                                </form>
                            </td>
                            <td>{{ $rule->model }}</td>
                            <td class="text-right text-xs text-slate-500">Fixo</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center text-slate-500">Nenhuma regra cadastrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
