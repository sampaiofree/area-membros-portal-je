<!DOCTYPE html>
<html lang="pt-BR" x-data="{ mobileMenu: false }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'EduX')</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        @stack('styles')
        <style>
            .plyr__video-wrapper iframe{
                width: 1000% !important;
                margin-left: -450% !important;
                }
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="min-h-screen bg-gray-50 text-edux-text">
        @php
            use Illuminate\Support\Facades\Schema;

            $unreadCount = $unreadCount
                ?? (
                    Schema::hasTable('notifications')
                    && Schema::hasColumn('notifications', 'notifiable_type')
                    && Schema::hasColumn('notifications', 'notifiable_id')
                    && auth()->check()
                        ? auth()->user()->unreadNotifications()->count()
                        : 0
                );
            $routeName = request()->route()?->getName();
            $navActive = match (true) {
                str_starts_with($routeName ?? '', 'learning.courses.') => 'cursos',
                ($routeName === 'dashboard' && request('tab') === 'vitrine') => 'vitrine',
                ($routeName === 'dashboard' && request('tab') === 'notificacoes') => 'notificacoes',
                ($routeName === 'dashboard' && request('tab') === 'suporte') => 'suporte',
                ($routeName === 'dashboard' && request('tab') === 'conta') => 'conta',
                default => 'painel',
            };
        @endphp

        {{-- Cabeçalho simples para o ambiente do aluno --}}
        <header class="sticky top-0 z-40 bg-white text-gray-800 shadow-sm">
            <div class="mx-auto max-w-6xl px-4 py-3">
                <div class="flex items-center justify-between">
                    <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-blue-600">EduX</a>

                    {{-- Menu Desktop --}}
                    <nav class="hidden items-center gap-4 md:flex">
                        <a href="{{ route('dashboard') }}" class="font-semibold text-gray-700 hover:text-blue-600">Início</a>
                        <a href="{{ route('dashboard', ['tab' => 'cursos']) }}" class="font-semibold text-gray-700 hover:text-blue-600">Meus Cursos</a>
                        <a href="{{ route('dashboard', ['tab' => 'vitrine']) }}" class="font-semibold text-gray-700 hover:text-blue-600">Vitrine</a>
                        <a href="{{ route('account.edit') }}" class="font-semibold text-gray-700 hover:text-blue-600">Minha Conta</a>
                        {{-- Logout --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="font-semibold text-gray-700 hover:text-blue-600">Sair</button>
                        </form>
                    </nav>

                    {{-- Ícone de notificações (mobile) --}}
                    <a href="{{ route('learning.notifications.index') }}" class="relative p-2 rounded-full hover:bg-gray-100 md:hidden">
                        @if ($unreadCount > 0)
                            <span class="absolute -top-0.5 -right-0.5 inline-flex h-4 min-w-[1rem] items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-semibold text-white">
                                {{ $unreadCount }}
                            </span>
                        @endif
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </a>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-6xl space-y-6 px-4 py-8">
            @if (session('status'))
                <div class="rounded-lg border-l-4 border-emerald-500 bg-emerald-50 p-4 text-emerald-900">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-lg border-l-4 border-red-500 bg-red-50 p-4 text-red-900">
                    <strong class="font-semibold">Atenção</strong>
                    <ul class="mt-2 list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="hidden bg-gray-100 text-gray-600 md:block">
            <div class="mx-auto max-w-6xl px-4 py-6 text-center">
                <p class="font-semibold">© {{ now()->year }} EduX — Aprender é simples.</p>
            </div>
        </footer>

        @auth
            @if (auth()->user()->isStudent())
                <livewire:student.notification-modal />
            @endif
        @endauth

        @livewireScripts
        @stack('scripts')
        <x-student-bottom-nav :active="$navActive" />
    </body>
</html>
