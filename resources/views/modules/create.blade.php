@extends('layouts.app')

@section('title', 'Novo módulo')

@section('content')
    <h1 style="margin-top:0;">Adicionar módulo · {{ $course->title }}</h1>

    @include('modules.partials.form', [
        'action' => route('courses.modules.store', $course),
        'method' => 'POST',
        'submitLabel' => 'Criar módulo',
        'module' => $module,
        'course' => $course,
    ])
@endsection
