@extends('layouts.app')

@section('title', 'Área do aluno')

@section('content')

@php
        $availableTabs = ['painel', 'cursos', 'vitrine', 'notificacoes', 'suporte', 'conta'];
        $initialTab = in_array(request('tab'), $availableTabs, true) ? request('tab') : 'painel';
    @endphp
    <div x-data="{ tab: @js($initialTab), moreOpen: false }" @set-tab.window="tab = $event.detail; moreOpen = false" class="min-h-screen flex flex-col">
        <main class="flex-1 mx-auto w-full max-w-md px-4 pt-4 pb-32 space-y-4">
            {{-- Painel (Home) --}}
            <section x-show="tab === 'painel'" x-cloak class="space-y-4">
                <livewire:student.panel-summary :user-id="$user->id" />
            </section>

            {{-- Meus cursos --}}
            <section x-show="tab === 'cursos'" x-cloak class="space-y-4">
                <livewire:student.dashboard :user-id="$user->id" />
            </section>

            {{-- Vitrine --}}
            <section x-show="tab === 'vitrine'" x-cloak class="space-y-4">
                <livewire:student.catalog />
            </section>

            {{-- Notificacoes --}}
            <section x-show="tab === 'notificacoes'" x-cloak class="space-y-4">
                <livewire:student.notifications-feed />
            </section>

            {{-- Suporte --}}
            <section x-show="tab === 'suporte'" x-cloak class="space-y-4">
                <div class="bg-white rounded-2xl shadow-sm p-4">
                    <p class="text-xs uppercase tracking-wide text-[#1A73E8]">Suporte</p>
                    <h2 class="text-xl font-bold text-gray-900">Como podemos ajudar?</h2>
                    <p class="mt-1 text-sm text-gray-600">Fale nos canais oficiais para tirar duvidas e acompanhar novidades.</p>
                    <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <a href="{{ config('services.support.instagram') }}" target="_blank" rel="noopener" class="rounded-xl bg-[#E8F1FD] py-3 px-4 text-center font-semibold text-[#1A73E8] shadow-sm">
                            Instagram
                        </a>
                        <a href="{{ config('services.support.whatsapp') }}" target="_blank" rel="noopener" class="rounded-xl bg-[#E9F9EC] py-3 px-4 text-center font-semibold text-green-700 shadow-sm">
                            Grupo no WhatsApp
                        </a>
                    </div>
                </div>
            </section>

            {{-- Minha conta --}}
            <section x-show="tab === 'conta'" x-cloak class="space-y-4">
                <div class="flex items-center justify-between rounded-2xl bg-white p-4 shadow-sm">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-[#1A73E8]">Conta</p>
                        <p class="text-base font-bold text-gray-900">{{ $user->preferredName() }}</p>
                        <p class="text-sm text-gray-600">{{ $user->email }}</p>
                    </div>
                    <a href="{{ route('account.edit') }}" class="rounded-xl bg-[#FBC02D] px-3 py-2 font-bold text-black shadow">
                        Editar
                    </a>
                </div>
                <div class="rounded-2xl bg-white p-4 shadow-sm">
                    <p class="text-sm font-semibold text-gray-800">Precisa de ajuda?</p>
                    <p class="mt-1 text-sm text-gray-600">Acesse suporte ou atualize seus dados acima.</p>
                </div>
            </section>
        </main>

        <nav class="fixed inset-x-0 bottom-0 z-50 pb-4">
            <div class="relative mx-auto max-w-md px-4">
                <div class="flex items-center justify-between gap-2 rounded-3xl bg-white px-4 py-3 shadow-xl">
                    <button type="button" @click="tab = 'painel'; moreOpen = false" :class="tab === 'painel' ? 'text-[#1A73E8]' : 'text-[#555]'" class="flex flex-1 flex-col items-center gap-1 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M3 9.75L12 4l9 5.75V20a1 1 0 01-1 1h-5.5v-5.5h-5V21H4a1 1 0 01-1-1V9.75z" />
                        </svg>
                        <span class="text-[11px] font-semibold">Home</span>
                    </button>
                    <button type="button" @click="tab = 'cursos'; moreOpen = false" :class="tab === 'cursos' ? 'text-[#1A73E8]' : 'text-[#555]'" class="flex flex-1 flex-col items-center gap-1 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M4.5 6.75h15M4.5 12h15M4.5 17.25h8.25" />
                        </svg>
                        <span class="text-[11px] font-semibold">Cursos</span>
                    </button>
                    <button type="button" @click="moreOpen = !moreOpen" :class="moreOpen ? 'text-[#1A73E8]' : 'text-[#555]'" class="flex flex-1 flex-col items-center gap-1 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 12h.01M12 12h.01M19 12h.01" />
                        </svg>
                        <span class="text-[11px] font-semibold">Mais</span>
                    </button>
                </div>

                <div x-show="moreOpen" x-transition x-cloak class="absolute bottom-full left-0 right-0 mb-2 space-y-2 rounded-2xl bg-white p-3 shadow-lg">
                    <button type="button" @click="tab = 'vitrine'; moreOpen = false" class="flex w-full items-center justify-between rounded-xl px-3 py-2 text-sm font-semibold text-[#555] hover:bg-gray-50">
                        <span>Vitrine</span>
                        <span class="text-xs text-[#1A73E8]">Ver</span>
                    </button>
                    <button type="button" @click="tab = 'notificacoes'; moreOpen = false" class="flex w-full items-center justify-between rounded-xl px-3 py-2 text-sm font-semibold text-[#555] hover:bg-gray-50">
                        <span>Notificacoes</span>
                        <span class="text-xs text-[#1A73E8]">Ver</span>
                    </button>
                    <button type="button" @click="tab = 'suporte'; moreOpen = false" class="flex w-full items-center justify-between rounded-xl px-3 py-2 text-sm font-semibold text-[#555] hover:bg-gray-50">
                        <span>Suporte</span>
                        <span class="text-xs text-[#1A73E8]">Ver</span>
                    </button>
                    <button type="button" @click="tab = 'conta'; moreOpen = false" class="flex w-full items-center justify-between rounded-xl px-3 py-2 text-sm font-semibold text-[#555] hover:bg-gray-50">
                        <span>Minha conta</span>
                        <span class="text-xs text-[#1A73E8]">Ver</span>
                    </button>
                </div>
            </div>
        </nav>
    </div>
    @livewireScripts
@endsection