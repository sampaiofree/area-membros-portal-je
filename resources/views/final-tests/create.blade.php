@extends('layouts.app')

@section('title', 'Novo teste final')

@section('content')
    <h1 style="margin-top:0;">Teste final · {{ $course->title }}</h1>

    @include('final-tests.partials.form', [
        'action' => route('courses.final-test.store', $course),
        'method' => 'POST',
        'submitLabel' => 'Criar teste final',
        'course' => $course,
        'finalTest' => $finalTest,
    ])
@endsection
