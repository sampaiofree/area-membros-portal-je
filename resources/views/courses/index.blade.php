@extends('layouts.app')

@section('title', 'Cursos')

@section('content')
    <section class="flex flex-wrap items-center justify-between gap-3 rounded-card bg-white p-4 shadow-card">
        <div>
            <p class="text-sm uppercase tracking-wide text-edux-primary">Catálogo</p>
            <h1 class="font-display text-3xl text-edux-primary">Cursos</h1>
        </div>
        <a href="{{ route('courses.create') }}" class="edux-btn">➕ Novo curso</a>
    </section>

    <div class="mt-6 overflow-hidden rounded-card bg-white shadow-card">
        <table class="min-w-full divide-y divide-edux-line">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-6 py-4">Nome</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Responsável</th>
                    <th class="px-6 py-4 text-right">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-edux-line">
                @forelse ($courses as $course)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <p class="font-semibold">{{ $course->title }}</p>
                            <p class="text-sm text-slate-500">{{ $course->summary }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span @class([
                                'inline-flex rounded-full px-3 py-1 text-xs font-semibold',
                                'bg-amber-100 text-amber-800' => $course->status === 'draft',
                                'bg-emerald-100 text-emerald-800' => $course->status === 'published',
                                'bg-slate-200 text-slate-700' => $course->status === 'archived',
                            ])>
                                {{ ucfirst($course->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">{{ $course->owner->name }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('courses.edit', $course) }}" class="text-edux-primary underline-offset-2 hover:underline">
                                Editar
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-6 text-center text-slate-500">Nenhum curso cadastrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $courses->links() }}
    </div>
@endsection
