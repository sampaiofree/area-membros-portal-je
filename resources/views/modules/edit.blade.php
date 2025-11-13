@extends('layouts.app')

@section('title', 'Editar módulo')

@section('content')
    <h1 style="margin-top:0;">Editar módulo · {{ $module->title }}</h1>

    @include('modules.partials.form', [
        'action' => route('modules.update', $module),
        'method' => 'PUT',
        'submitLabel' => 'Salvar módulo',
        'module' => $module,
        'course' => $course,
    ])
@endsection
