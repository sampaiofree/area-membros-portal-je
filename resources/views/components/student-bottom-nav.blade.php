@props(['active' => 'painel'])

@php
    $primaryItems = [
        'cursos' => [
            'route' => route('dashboard', ['tab' => 'cursos']),
            'label' => 'Cursos',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6.75h16M4 12h16M6 17.25h8"/></svg>',
        ],
        'vitrine' => [
            'route' => route('dashboard', ['tab' => 'vitrine']),
            'label' => 'Vitrine',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>',
        ],
    ];

    $moreItems = [
        'painel' => ['route' => route('dashboard'), 'label' => 'Home'],
        'notificacoes' => ['route' => route('dashboard', ['tab' => 'notificacoes']), 'label' => 'Avisos'],
        'suporte' => ['route' => route('dashboard', ['tab' => 'suporte']), 'label' => 'Suporte'],
        'conta' => ['route' => route('dashboard', ['tab' => 'conta']), 'label' => 'Conta'],
    ];

    $moreActive = array_key_exists($active, $moreItems);
@endphp

<nav class="fixed inset-x-0 bottom-0 z-50 border-t bg-white shadow-lg md:hidden" x-data="{ moreOpen: false }" @click.away="moreOpen = false">
    <div class="mx-auto max-w-md px-2">
        <div class="flex justify-around">
            @foreach ($primaryItems as $key => $item)
                @php $isActive = $key === $active; @endphp
                <a href="{{ $item['route'] }}"
                    @class([
                        'flex w-full flex-col items-center justify-center pt-2 pb-1 text-center transition-colors',
                        'text-blue-600' => $isActive,
                        'text-gray-500 hover:text-blue-600' => ! $isActive,
                    ])>
                    {!! $item['icon'] !!}
                    <span class="mt-1 text-[11px] font-semibold">{{ $item['label'] }}</span>
                </a>
            @endforeach

            <button type="button"
                class="flex w-full flex-col items-center justify-center pt-2 pb-1 text-center transition-colors focus:outline-none"
                :class="moreOpen || {{ $moreActive ? 'true' : 'false' }} ? 'text-blue-600' : 'text-gray-500 hover:text-blue-600'"
                @click="moreOpen = !moreOpen">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01" />
                </svg>
                <span class="mt-1 text-[11px] font-semibold">Mais</span>
            </button>
        </div>

        <div x-show="moreOpen" x-transition x-cloak class="absolute bottom-full left-0 right-0 mb-2 space-y-1 rounded-2xl bg-white p-3 shadow-lg">
            @foreach ($moreItems as $key => $item)
                @php $isActive = $key === $active; @endphp
                <a href="{{ $item['route'] }}"
                    @class([
                        'flex w-full items-center justify-between rounded-xl px-3 py-2 text-sm font-semibold transition-colors',
                        'text-blue-600 bg-blue-50' => $isActive,
                        'text-gray-600 hover:bg-gray-50' => ! $isActive,
                    ])>
                    <span>{{ $item['label'] }}</span>
                    <span class="text-xs text-blue-500">Ver</span>
                </a>
            @endforeach
        </div>
    </div>
</nav>

<div class="pb-16 md:hidden"></div>
