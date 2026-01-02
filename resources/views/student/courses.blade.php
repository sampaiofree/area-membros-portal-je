<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Meus cursos</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F5F5F5] min-h-screen font-sans">
    <main class="mx-auto max-w-sm px-4 pt-4 pb-24">

        {{-- Ordem: últimos acessados primeiro, depois demais (implementar na query/controller) --}}

        {{-- Barra de busca (somente UI por enquanto) --}}
        <div class="mb-4">
            <div class="flex items-center bg-white rounded-full shadow px-4 py-2">
                <span class="text-xl mr-2">🔍</span>
                <input
                    type="text"
                    placeholder="Buscar curso..."
                    class="flex-1 outline-none text-sm bg-transparent"
                >
            </div>
        </div>

        @if(isset($courses) && count($courses))
            <section class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                @foreach($courses as $course)
                    @php
                        $progress = $course->progress ?? 0;
                        $image = $course->image_url ?? asset('images/default-course.png');
                    @endphp
                    <div class="bg-white rounded-2xl shadow-sm p-3 flex flex-col aspect-square">
                        <img
                            src="{{ $image }}"
                            alt="Capa do curso {{ $course->title }}"
                            class="w-full h-24 object-cover rounded-xl"
                        >

                        <span class="mt-2 font-bold text-sm line-clamp-2 text-gray-800">
                            {{ $course->title }}
                        </span>

                        <div class="mt-2">
                            <div class="w-full h-2 bg-gray-200 rounded-full">
                                <div
                                    class="h-2 bg-[#1A73E8] rounded-full"
                                    style="width: {{ $progress }}%"
                                ></div>
                            </div>
                            <span class="text-[11px] text-gray-600">{{ $progress }}% concluído</span>
                        </div>
                    </div>
                @endforeach
            </section>

            <button class="w-full bg-[#FBC02D] text-black font-bold py-3 rounded-xl mt-6">
                Carregar mais cursos
            </button>
        @else
            <div class="text-center py-20">
                <div class="text-6xl mb-4">🎓</div>
                <p class="font-bold text-gray-700 mb-3">Você ainda não tem cursos.</p>
                <button class="bg-[#FBC02D] text-black font-bold py-3 px-6 rounded-xl">
                    Ver vitrine
                </button>
            </div>
        @endif
    </main>

    <nav class="fixed inset-x-0 bottom-0 z-50 pb-4">
        <div class="mx-auto max-w-md px-4">
            <div class="bg-white rounded-2xl shadow border flex items-center justify-between px-6 py-3">
                <a href="{{ route('design.student.dashboard') }}" class="flex flex-col items-center gap-1 text-[#555]">
                    <span class="text-2xl">🏠</span>
                    <span class="text-xs font-semibold">Home</span>
                </a>
                <a href="{{ route('design.student.courses') }}" class="flex flex-col items-center gap-1 text-[#1A73E8]">
                    <span class="text-2xl">🎓</span>
                    <span class="text-xs font-semibold">Cursos</span>
                </a>
                <button type="button" class="flex flex-col items-center gap-1 text-[#555]">
                    <span class="text-2xl">☰</span>
                    <span class="text-xs font-semibold">Mais</span>
                </button>
            </div>
        </div>
    </nav>
</body>
</html>
