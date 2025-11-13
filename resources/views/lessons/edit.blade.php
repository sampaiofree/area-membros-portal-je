@extends('layouts.app')

@section('title', 'Editar aula')

@section('content')
    <h1 style="margin-top:0;">Editar aula · {{ $lesson->title }}</h1>
    @include('lessons.partials.form', [
        'action' => route('lessons.update', $lesson),
        'method' => 'PUT',
        'submitLabel' => 'Salvar aula',
        'lesson' => $lesson,
        'module' => $module,
        'course' => $course,
    ])
@endsection
