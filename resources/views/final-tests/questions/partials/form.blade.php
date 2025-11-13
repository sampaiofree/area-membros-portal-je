<form method="POST" action="{{ $action }}" class="card" style="display:flex; flex-direction:column; gap:1rem;">
    @csrf
    @if (! in_array($method, ['POST', null], true))
        @method($method)
    @endif

    <label style="display:flex; flex-direction:column; gap:0.25rem;">
        <span>Título</span>
        <input type="text" name="title" value="{{ old('title', $question->title) }}" required style="padding:0.75rem; border-radius:8px; border:1px solid #cbd5f5;">
    </label>

    <label style="display:flex; flex-direction:column; gap:0.25rem;">
        <span>Enunciado</span>
        <textarea name="statement" rows="4" style="padding:0.75rem; border-radius:8px; border:1px solid #cbd5f5;">{{ old('statement', $question->statement) }}</textarea>
    </label>

    <div style="display:flex; gap:1rem; flex-wrap:wrap;">
        <label style="flex:1; display:flex; flex-direction:column; gap:0.25rem; min-width:120px;">
            <span>Posição</span>
            <input type="number" min="1" name="position" value="{{ old('position', $question->position) }}" required style="padding:0.75rem; border-radius:8px; border:1px solid #cbd5f5;">
        </label>
        <label style="flex:1; display:flex; flex-direction:column; gap:0.25rem; min-width:120px;">
            <span>Peso</span>
            <input type="number" min="1" max="10" name="weight" value="{{ old('weight', $question->weight) }}" required style="padding:0.75rem; border-radius:8px; border:1px solid #cbd5f5;">
        </label>
    </div>

    <div style="display:flex; gap:0.5rem;">
        <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
        <a href="{{ route('courses.final-test.questions.index', [$course, $finalTest]) }}" class="btn btn-secondary">Cancelar</a>
    </div>
</form>
