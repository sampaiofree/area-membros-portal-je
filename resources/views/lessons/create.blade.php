@extends('layouts.app')

@section('title', 'Nova aula')

@section('content')
    <h1 style="margin-top:0;">Nova aula · {{ $module->title }}</h1>
    @include('lessons.partials.form', [
        'action' => route('modules.lessons.store', $module),
        'method' => 'POST',
        'submitLabel' => 'Criar aula',
        'lesson' => $lesson,
        'module' => $module,
        'course' => $course,
    ])
@endsection
