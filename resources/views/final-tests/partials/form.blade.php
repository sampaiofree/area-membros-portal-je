<form method="POST" action="{{ $action }}" class="card" style="display:flex; flex-direction:column; gap:1rem; max-width:600px;">
    @csrf
    @if (! in_array($method, ['POST', null], true))
        @method($method)
    @endif

    <label style="display:flex; flex-direction:column; gap:0.25rem;">
        <span>Título do teste</span>
        <input type="text" name="title" value="{{ old('title', $finalTest->title ?? '') }}" required style="padding:0.75rem; border-radius:8px; border:1px solid #cbd5f5;">
    </label>

    <label style="display:flex; flex-direction:column; gap:0.25rem;">
        <span>Instruções</span>
        <textarea name="instructions" rows="4" style="padding:0.75rem; border-radius:8px; border:1px solid #cbd5f5;">{{ old('instructions', $finalTest->instructions ?? '') }}</textarea>
    </label>

    <label style="display:flex; flex-direction:column; gap:0.25rem;">
        <span>Nota mínima (%)</span>
        <input type="number" name="passing_score" value="{{ old('passing_score', $finalTest->passing_score ?? 70) }}" min="1" max="100" required style="padding:0.75rem; border-radius:8px; border:1px solid #cbd5f5;">
    </label>

    <label style="display:flex; flex-direction:column; gap:0.25rem;">
        <span>Máximo de tentativas</span>
        <input type="number" name="max_attempts" value="{{ old('max_attempts', $finalTest->max_attempts ?? 1) }}" min="1" max="10" required style="padding:0.75rem; border-radius:8px; border:1px solid #cbd5f5;">
    </label>

    <label style="display:flex; flex-direction:column; gap:0.25rem;">
        <span>Duração (minutos)</span>
        <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $finalTest->duration_minutes ?? '') }}" min="5" style="padding:0.75rem; border-radius:8px; border:1px solid #cbd5f5;">
    </label>

    <div style="display:flex; gap:0.5rem;">
        <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
        <a href="{{ route('courses.edit', $course) }}" class="btn btn-secondary">Cancelar</a>
    </div>
</form>
