<div class="space-y-6">
    <div class="rounded-card bg-white p-4 shadow-card flex flex-wrap items-center gap-4">
        <label class="flex-1 text-sm font-semibold text-slate-600">
            <span class="sr-only">Buscar curso</span>
            <input type="search" wire:model.debounce.500ms="search" placeholder="Buscar curso..."
                class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30">
        </label>
        <label class="text-sm font-semibold text-slate-600">
            <span class="sr-only">Status</span>
            <select wire:model.live="status" class="rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30">
                <option value="all">Todos</option>
                <option value="draft">Rascunho</option>
                <option value="published">Publicado</option>
                <option value="archived">Arquivado</option>
            </select>
        </label>
        <a href="{{ route('courses.create') }}" class="edux-btn">
            + Novo curso
        </a>
    </div>

    <div class="grid gap-4 md:grid-cols-3">
        @forelse ($courses as $course)
            <article class="rounded-card bg-white shadow-card overflow-hidden flex flex-col" wire:key="course-{{ $course->id }}">
                @if ($course->coverImageUrl())
                    <img src="{{ $course->coverImageUrl() }}" alt="{{ $course->title }}" class="h-36 w-full object-cover">
                @else
                    <div class="h-36 w-full bg-edux-background flex items-center justify-center text-slate-400 text-sm">Sem imagem</div>
                @endif
                <div class="flex flex-1 flex-col gap-3 p-4">
                    <div class="flex items-center justify-between text-xs text-slate-500">
                        <span class="font-semibold text-edux-primary">{{ Str::limit($course->title, 26) }}</span>
                        <span @class([
                            'inline-flex rounded-full px-3 py-0.5 text-xs font-semibold',
                            'bg-amber-100 text-amber-800' => $course->status === 'draft',
                            'bg-emerald-100 text-emerald-800' => $course->status === 'published',
                            'bg-slate-200 text-slate-700' => $course->status === 'archived',
                        ])>
                            {{ ucfirst($course->status) }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500">Responsável: {{ $course->owner->name }}</p>
                    <div class="mt-auto flex gap-2 text-sm">
                        <a href="{{ route('courses.edit', $course) }}" class="edux-btn flex-1 bg-white text-edux-primary">Editar</a>
                        <form method="POST" action="{{ route('courses.destroy', $course) }}" class="flex-1" onsubmit="return confirm('Remover curso?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="edux-btn w-full bg-red-500 text-white">Excluir</button>
                        </form>
                    </div>
                </div>
            </article>
        @empty
            <div class="rounded-card bg-white p-6 text-center text-slate-500 shadow-card md:col-span-3">
                Nenhum curso encontrado.
            </div>
        @endforelse
    </div>

    <div>
        {{ $courses->links() }}
    </div>
</div>
