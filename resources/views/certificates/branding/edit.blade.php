@extends('layouts.app')

@section('title', 'Certificado · Imagens padrão')

@section('content')
    <section class="space-y-3 rounded-card bg-white p-6 shadow-card">
        <h1 class="font-display text-3xl text-edux-primary">Modelos padrão do certificado</h1>
        <p class="text-slate-600">Estas imagens serão usadas em todos os cursos que não definirem um fundo próprio.</p>

        <form method="POST" action="{{ route('certificates.branding.update') }}" enctype="multipart/form-data" class="mt-4 space-y-6">
            @csrf
            @method('PUT')

            <div class="space-y-2">
                <label class="text-sm font-semibold text-slate-600">Imagem da frente</label>
                <input type="file" name="front_background" accept="image/*" class="w-full rounded-xl border border-edux-line px-4 py-3">
                @if ($branding->front_background_url)
                    <img src="{{ $branding->front_background_url }}" alt="Frente atual" class="mt-3 rounded-xl border border-edux-line">
                @endif
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-slate-600">Imagem do verso</label>
                <input type="file" name="back_background" accept="image/*" class="w-full rounded-xl border border-edux-line px-4 py-3">
                @if ($branding->back_background_url)
                    <img src="{{ $branding->back_background_url }}" alt="Verso atual" class="mt-3 rounded-xl border border-edux-line">
                @endif
            </div>

            <button type="submit" class="edux-btn">Salvar imagens</button>
        </form>
    </section>
@endsection
