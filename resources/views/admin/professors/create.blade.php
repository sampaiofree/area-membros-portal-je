@extends('layouts.app')

@section('title', 'Novo professor')

@section('content')
    <section class="space-y-6">
        <header class="rounded-card bg-white p-6 shadow-card">
            <p class="text-sm uppercase tracking-wide text-edux-primary">Equipe</p>
            <h1 class="font-display text-3xl text-edux-primary">Cadastrar professor</h1>
            <p class="text-slate-600">Crie um usuário com perfil de professor para que ele possa gerenciar cursos e aulas.</p>
        </header>

        <livewire:admin.professor-form />
    </section>
@endsection
