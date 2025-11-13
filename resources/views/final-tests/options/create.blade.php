@extends('layouts.app')

@section('title', 'Nova alternativa')

@section('content')
    <h1 style="margin-top:0;">Nova alternativa · {{ $question->title }}</h1>

    @include('final-tests.options.partials.form', [
        'action' => route('courses.final-test.questions.options.store', [$course, $finalTest, $question]),
        'method' => 'POST',
        'submitLabel' => 'Adicionar alternativa',
        'option' => $option,
        'course' => $course,
        'finalTest' => $finalTest,
        'question' => $question,
    ])
@endsection
