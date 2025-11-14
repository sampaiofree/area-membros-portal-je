@extends('layouts.app')

@section('title', 'Novo curso')

@section('content')
    <section class="rounded-card bg-white p-6 shadow-card space-y-3">
        <p class="text-sm uppercase tracking-wide text-edux-primary">Catálogo</p>
        <h1 class="font-display text-3xl text-edux-primary">Criar curso</h1>
        <p class="text-slate-600">Defina título, resumo, certificado e publique quando estiver pronto.</p>
    </section>

    @include('courses.partials.form', ['course' => $course, 'teachers' => $teachers, 'user' => $user])
@endsection
