@extends('layouts.app')

@section('title', 'Novo registro Kavoo')

@section('content')
    <section class="space-y-6">
        <header class="rounded-card bg-white p-6 shadow-card flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-sm uppercase tracking-wide text-edux-primary">Kavoo</p>
                <h1 class="font-display text-3xl text-edux-primary">Novo registro</h1>
                <p class="text-slate-600 text-sm">Cadastre dados novos e complementares ao que foi recebido via webhook.</p>
            </div>
            <a href="{{ route('admin.kavoo.index') }}" class="edux-btn bg-white text-edux-primary">
                Voltar para a lista
            </a>
        </header>

        <form method="POST" action="{{ route('admin.kavoo.store') }}" class="rounded-card bg-white p-6 shadow-card space-y-5">
            @csrf

            @include('admin.kavoo.form-fields')

            <div class="flex flex-wrap gap-3">
                <button type="submit" class="edux-btn">Salvar registro</button>
                <a href="{{ route('admin.kavoo.index') }}" class="edux-btn bg-white text-edux-primary">Cancelar</a>
            </div>
        </form>
    </section>
@endsection
