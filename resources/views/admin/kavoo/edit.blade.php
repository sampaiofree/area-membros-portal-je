@extends('layouts.app')

@section('title', 'Editar registro Kavoo')

@section('content')
    <section class="space-y-6">
        <header class="rounded-card bg-white p-6 shadow-card flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-sm uppercase tracking-wide text-edux-primary">Kavoo</p>
                <h1 class="font-display text-3xl text-edux-primary">Editar {{ $kavoo->transaction_code ?? '#'.$kavoo->id }}</h1>
                <p class="text-slate-600 text-sm">Faça ajustes nos campos básicos que o sistema utiliza automaticamente.</p>
            </div>
            <a href="{{ route('admin.kavoo.index') }}" class="edux-btn bg-white text-edux-primary">
                Voltar para a lista
            </a>
        </header>

        <form method="POST" action="{{ route('admin.kavoo.update', $kavoo) }}" class="rounded-card bg-white p-6 shadow-card space-y-5">
            @csrf
            @method('PUT')

            @include('admin.kavoo.form-fields', ['kavoo' => $kavoo])

            <div class="flex flex-wrap gap-3">
                <button type="submit" class="edux-btn">Salvar alterações</button>
                <a href="{{ route('admin.kavoo.index') }}" class="edux-btn bg-white text-edux-primary">Cancelar</a>
            </div>
        </form>
    </section>
@endsection
