@extends('layouts.app')

@section('title', 'Editar alternativa')

@section('content')
    <h1 style="margin-top:0;">Editar alternativa · {{ $question->title }}</h1>

    @include('final-tests.options.partials.form', [
        'action' => route('courses.final-test.questions.options.update', [$course, $finalTest, $question, $option]),
        'method' => 'PUT',
        'submitLabel' => 'Salvar alternativa',
        'option' => $option,
        'course' => $course,
        'finalTest' => $finalTest,
        'question' => $question,
    ])
@endsection
