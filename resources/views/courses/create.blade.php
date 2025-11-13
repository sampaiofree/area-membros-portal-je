@extends('layouts.app')

@section('title', 'Novo curso')

@section('content')
    <section class="rounded-card bg-white p-6 shadow-card">
        <p class="text-sm uppercase tracking-wide text-edux-primary">Novo curso</p>
        <h1 class="font-display text-3xl text-edux-primary">Criar curso</h1>
        <p class="text-slate-600">Defina título, resumo e personalize o certificado do curso.</p>
    </section>

    @include('courses.partials.form', [
        'action' => route('courses.store'),
        'method' => 'POST',
        'submitLabel' => 'Criar curso',
        'course' => $course,
        'teachers' => $teachers,
        'user' => $user,
    ])
@endsection
