@extends('layouts.app')

@section('title', 'Minha conta')

@section('content')
    <section class="space-y-6">
        <header class="rounded-card bg-white p-6 shadow-card">
            <p class="text-sm uppercase tracking-wide text-edux-primary">Configurações</p>
            <h1 class="font-display text-3xl text-edux-primary">Perfil e segurança</h1>
            <p class="text-slate-600">Atualize seus dados básicos, qualificação e foto de perfil.</p>
        </header>

        <livewire:account.profile-form />
    </section>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/trix@2.1.0/dist/trix.umd.min.js" defer></script>
@endpush
