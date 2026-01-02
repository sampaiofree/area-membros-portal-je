@extends('layouts.app')

@section('title', 'Identidade visual')

@section('content')
    <section class="rounded-card bg-white p-6 shadow-card space-y-3">
        <p class="text-sm uppercase tracking-wide text-edux-primary">Branding</p>
        <h1 class="font-display text-3xl text-edux-primary">Identidade visual da plataforma</h1>
        <p class="text-slate-600">
            Envie as artes padrão. Elas serão usadas automaticamente sempre que um curso, módulo ou aula não tiver mídia própria.
        </p>
    </section>

    <form method="POST" action="{{ route('admin.identity.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="rounded-card bg-white p-6 shadow-card space-y-6">
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($fields as $name => $field)
                    @php
                        $column = $field['column'];
                        $preview = $settings->assetUrl($column);
                    @endphp
                    <article class="space-y-3 rounded-2xl border border-edux-line/60 p-4" key="field-{{ $name }}">
                        <div class="h-32 w-full overflow-hidden rounded-xl bg-edux-background flex items-center justify-center">
                            @if ($preview)
                                <img src="{{ $preview }}" alt="{{ $field['label'] }}" class="h-full w-full object-cover">
                            @else
                                <span class="text-xs text-slate-400">Sem imagem</span>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-lg font-display text-edux-primary">{{ $field['label'] }}</h3>
                            <p class="text-xs text-slate-500">{{ $field['description'] }}</p>
                        </div>
                        <label class="block text-sm font-semibold text-slate-600">
                            <span>Selecionar arquivo</span>
                            <input type="file" name="{{ $name }}" accept="image/*"
                                class="mt-2 block w-full rounded-xl border border-dashed border-edux-line px-4 py-2 text-sm file:mr-4 file:rounded-full file:border-0 file:bg-edux-primary file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white focus:border-edux-primary focus:ring-edux-primary/30">
                        </label>
                        @error($name)
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </article>
                @endforeach
            </div>

            <div class="text-right">
                <button type="submit" class="edux-btn">
                    Salvar alterações
                </button>
            </div>
        </div>
    </form>
@endsection
