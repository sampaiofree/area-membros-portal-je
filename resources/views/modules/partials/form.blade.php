<form method="POST" action="{{ $action }}" class="card" style="display:flex; flex-direction:column; gap:1rem; max-width:600px;">
    @csrf
    @if (! in_array($method, ['POST', null], true))
        @method($method)
    @endif

    <label style="display:flex; flex-direction:column; gap:0.25rem;">
        <span>Título do módulo</span>
        <input type="text" name="title" value="{{ old('title', $module->title) }}" required style="padding:0.75rem; border-radius:8px; border:1px solid #cbd5f5;">
    </label>

    <label style="display:flex; flex-direction:column; gap:0.25rem;">
        <span>Descrição</span>
        <textarea name="description" rows="3" style="padding:0.75rem; border-radius:8px; border:1px solid #cbd5f5;">{{ old('description', $module->description) }}</textarea>
    </label>

    <label style="display:flex; flex-direction:column; gap:0.25rem;">
        <span>Posição</span>
        <input type="number" name="position" min="1" value="{{ old('position', $module->position) }}" style="padding:0.75rem; border-radius:8px; border:1px solid #cbd5f5;">
    </label>

    <div style="display:flex; gap:0.5rem;">
        <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
        <a href="{{ route('courses.edit', $course) }}" class="btn btn-secondary">Cancelar</a>
    </div>
</form>
