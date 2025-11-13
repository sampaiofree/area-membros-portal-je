@extends('layouts.app')

@section('title', 'Editar teste final')

@section('content')
    <h1 style="margin-top:0;">Editar teste final · {{ $course->title }}</h1>

    @include('final-tests.partials.form', [
        'action' => route('courses.final-test.update', [$course, $finalTest]),
        'method' => 'PUT',
        'submitLabel' => 'Salvar teste final',
        'course' => $course,
        'finalTest' => $finalTest,
    ])
@endsection
