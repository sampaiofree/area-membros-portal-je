<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="edux-card space-y-5">
    @csrf
    @if (! in_array($method, ['POST', null], true))
        @method($method)
    @endif

    <label class="space-y-2 text-sm font-semibold text-slate-600">
        <span>Título</span>
        <input type="text" name="title" value="{{ old('title', $course->title) }}" required class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30">
    </label>

    <label class="space-y-2 text-sm font-semibold text-slate-600">
        <span>Resumo curto</span>
        <input type="text" name="summary" value="{{ old('summary', $course->summary) }}" class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30">
    </label>

    <label class="space-y-2 text-sm font-semibold text-slate-600">
        <span>Descrição completa</span>
        <textarea name="description" rows="4" class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30">{{ old('description', $course->description) }}</textarea>
    </label>

    <label class="space-y-2 text-sm font-semibold text-slate-600">
        <span>Status</span>
        <select name="status" class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30">
            @foreach (['draft' => 'Rascunho', 'published' => 'Publicado', 'archived' => 'Arquivado'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $course->status) === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </label>

    <label class="space-y-2 text-sm font-semibold text-slate-600">
        <span>Duração (minutos)</span>
        <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $course->duration_minutes) }}" min="1" class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30">
    </label>

    <label class="space-y-2 text-sm font-semibold text-slate-600">
        <span>Data de publicação</span>
        <input type="datetime-local" name="published_at" value="{{ old('published_at', optional($course->published_at)->format('Y-m-d\TH:i')) }}" class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30">
    </label>

    @if ($user->isAdmin())
        <label class="space-y-2 text-sm font-semibold text-slate-600">
            <span>Responsável</span>
            <select name="owner_id" class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30">
                @foreach ($teachers as $teacher)
                    <option value="{{ $teacher->id }}" @selected(old('owner_id', $course->owner_id ?? $user->id) == $teacher->id)>
                        {{ $teacher->name }} ({{ $teacher->role->label() }})
                    </option>
                @endforeach
            </select>
        </label>

        <div class="rounded-2xl border border-dashed border-edux-line p-4">
            <p class="font-semibold text-slate-700">Certificado · imagens exclusivas</p>
            <div class="mt-4 grid gap-4 md:grid-cols-2">
                <label class="space-y-2 text-sm font-semibold text-slate-600">
                    <span>Fundo da frente (opcional)</span>
                    <input type="file" name="certificate_front_background" accept="image/*" class="w-full rounded-xl border border-edux-line px-4 py-3">
                    @if(optional($course->certificateBranding)->front_background_url)
                        <img src="{{ $course->certificateBranding->front_background_url }}" alt="Fundo atual frente" class="rounded-xl border border-edux-line">
                    @endif
                </label>
                <label class="space-y-2 text-sm font-semibold text-slate-600">
                    <span>Fundo do verso (opcional)</span>
                    <input type="file" name="certificate_back_background" accept="image/*" class="w-full rounded-xl border border-edux-line px-4 py-3">
                    @if(optional($course->certificateBranding)->back_background_url)
                        <img src="{{ $course->certificateBranding->back_background_url }}" alt="Fundo atual verso" class="rounded-xl border border-edux-line">
                    @endif
                </label>
            </div>
        </div>
    @endif

    <div class="flex flex-wrap gap-3">
        <button type="submit" class="edux-btn">{{ $submitLabel }}</button>
        <a href="{{ route('courses.index') }}" class="edux-btn bg-white text-edux-primary">Cancelar</a>
    </div>
</form>
