@extends('layouts.app')

@section('title', 'Certificados gerados')

@section('content')
    <section class="space-y-6">
        <header class="rounded-card bg-white p-6 shadow-card flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-sm uppercase tracking-wide text-edux-primary">Certificados</p>
                <h1 class="font-display text-3xl text-edux-primary">Certificados gerados</h1>
                <p class="text-slate-600 text-sm">Consulte todos os certificados emitidos e baixe o PDF.</p>
            </div>
        </header>

        <div class="rounded-card bg-white p-6 shadow-card space-y-4">
            <form method="GET" class="flex flex-col gap-3 md:flex-row">
                <label class="flex-1 text-sm font-semibold text-slate-600">
                    <span class="sr-only">Buscar</span>
                    <input
                        type="search"
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Número, aluno, curso, e-mail ou ID"
                        class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30"
                    >
                </label>
                <button type="submit" class="edux-btn w-full md:w-auto">Buscar</button>
            </form>

            @if (session('status'))
                <p class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('status') }}
                </p>
            @endif

            <div class="overflow-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-slate-500 text-xs uppercase tracking-wide">
                            <th class="pb-2">Número</th>
                            <th class="pb-2">Curso</th>
                            <th class="pb-2">Aluno</th>
                            <th class="pb-2">Emitido em</th>
                            <th class="pb-2">Criado em</th>
                            <th class="pb-2 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($certificates as $certificate)
                            <tr>
                                <td class="py-3">
                                    <div class="font-semibold text-edux-primary">{{ $certificate->number }}</div>
                                    <p class="text-xs text-slate-500">ID #{{ $certificate->id }}</p>
                                </td>
                                <td class="py-3">
                                    <div class="font-semibold text-edux-primary">
                                        {{ $certificate->course?->title ?? '-' }}
                                    </div>
                                    <p class="text-xs text-slate-500">ID #{{ $certificate->course_id ?? '-' }}</p>
                                </td>
                                <td class="py-3">
                                    <div class="font-semibold text-edux-primary">
                                        {{ $certificate->user?->preferredName() ?? '-' }}
                                    </div>
                                    <p class="text-xs text-slate-500">
                                        {{ $certificate->user?->email ?? $certificate->user?->whatsapp ?? '-' }}
                                    </p>
                                </td>
                                <td class="py-3">{{ $certificate->issued_at?->format('d/m/Y H:i') ?? '-' }}</td>
                                <td class="py-3">{{ $certificate->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                                <td class="py-3 text-right">
                                    <a href="{{ route('admin.certificates.generated.download', $certificate) }}" class="text-edux-primary text-sm underline-offset-2 hover:underline">
                                        Baixar PDF
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 text-center text-sm text-slate-500">
                                    Nenhum certificado encontrado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $certificates->links() }}
        </div>
    </section>
@endsection
