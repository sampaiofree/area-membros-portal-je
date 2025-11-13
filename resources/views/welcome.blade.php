@extends('layouts.app')

@section('title', 'Bem-vindo ao EduX')

@section('content')
    <section class="edux-section rounded-card bg-white shadow-card">
        <div class="flex flex-col gap-6 md:flex-row md:items-center">
            <div class="space-y-4 md:w-1/2">
                <span class="inline-flex items-center gap-2 rounded-full bg-edux-primary/10 px-4 py-2 text-sm font-semibold text-edux-primary">
                    🚀 Plataforma EduX
                </span>
                <h1 class="text-4xl font-display text-edux-primary">
                    Aprender com clareza e agilidade ficou simples.
                </h1>
                <p class="text-lg">
                    Organize cursos, acompanhe aulas e gere certificados totalmente customizados – tudo em um único lugar.
                </p>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('login') }}" class="edux-btn">
                        👉 Começar agora
                    </a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="edux-btn bg-edux-primary text-white">
                            📚 Ir para o painel
                        </a>
                    @endauth
                </div>
            </div>
            <div class="md:w-1/2">
                <div class="edux-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm uppercase tracking-wide text-edux-primary/80">Status</p>
                            <p class="text-3xl font-display">+120 cursos ativos</p>
                        </div>
                        <span class="rounded-full bg-edux-cta/20 px-4 py-2 font-semibold text-edux-cta">100% online</span>
                    </div>
                    <ul class="mt-4 space-y-2 text-sm">
                        <li class="flex items-center gap-2">
                            <span class="text-edux-primary">✓</span>
                            Dashboard com drag & drop dos cursos
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-edux-primary">✓</span>
                            Modais rápidos para detalhes das aulas
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-edux-primary">✓</span>
                            Accordions para módulos e testes finais
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endsection
