@extends('layouts.app')

@section('title', 'Nova questão')

@section('content')
    <h1 style="margin-top:0;">Nova questão · {{ $course->title }}</h1>

    @include('final-tests.questions.partials.form', [
        'action' => route('courses.final-test.questions.store', [$course, $finalTest]),
        'method' => 'POST',
        'submitLabel' => 'Criar questão',
        'question' => $question,
        'course' => $course,
        'finalTest' => $finalTest,
    ])
@endsection
