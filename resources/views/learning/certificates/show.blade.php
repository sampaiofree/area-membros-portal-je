@extends('layouts.app')

@section('title', 'Certificado emitido')

@section('content')
    <h1 style="margin-top:0;">Certificado · {{ $course->title }}</h1>
    <p style="margin-top:0; color:#475569;">Número: {{ $certificate->number }} · Emitido em {{ $certificate->issued_at->format('d/m/Y') }}</p>

    <div style="display:flex; gap:0.75rem; flex-wrap:wrap; margin-bottom:1rem;">
        <a href="{{ route('learning.courses.certificate.download', [$course, $certificate]) }}" class="btn btn-primary">Baixar PDF</a>
        <a href="{{ $publicUrl }}" target="_blank" class="btn btn-secondary">Ver link público</a>
    </div>

    @if (auth()->user()->name_change_available)
        <div class="card" style="margin-bottom:1rem; border-left:4px solid #f97316;">
            <strong>Nome incorreto?</strong>
            <p style="margin:0.25rem 0 0 0;">
                Você pode alterar o nome apenas uma vez para que apareça corretamente no certificado.
                <a href="{{ route('learning.profile.name.edit') }}">Atualizar nome</a>.
            </p>
        </div>
    @endif

    <div style="display:flex; flex-direction:column; gap:1rem;">
        <div class="card" style="border-radius:20px;">
            {!! $certificate->front_content !!}
        </div>
        <div class="card" style="border-radius:20px;">
            {!! $certificate->back_content !!}
        </div>
    </div>

    <div style="margin-top:1.5rem;">
        <p style="margin:0 0 0.5rem 0; color:#475569;">Qualquer pessoa pode validar este certificado acessando: <strong>{{ $publicUrl }}</strong></p>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Voltar para dashboard</a>
    </div>
@endsection
