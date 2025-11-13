<form method="POST" action="{{ $action }}" class="card" style="display:flex; flex-direction:column; gap:1rem; max-width:600px;">
    @csrf
    @if (! in_array($method, ['POST', null], true))
        @method($method)
    @endif

    <label style="display:flex; flex-direction:column; gap:0.25rem;">
        <span>Texto da alternativa</span>
        <input type="text" name="label" value="{{ old('label', $option->label) }}" required style="padding:0.75rem; border-radius:8px; border:1px solid #cbd5f5;">
    </label>

    <div style="display:flex; gap:1rem; flex-wrap:wrap;">
        <label style="display:flex; align-items:center; gap:0.5rem;">
            <input type="checkbox" name="is_correct" value="1" @checked(old('is_correct', $option->is_correct))>
            <span>Resposta correta</span>
        </label>

        <label style="display:flex; flex-direction:column; gap:0.25rem; min-width:120px;">
            <span>Posição</span>
            <input type="number" min="1" name="position" value="{{ old('position', $option->position) }}" style="padding:0.75rem; border-radius:8px; border:1px solid #cbd5f5;">
        </label>
    </div>

    <div style="display:flex; gap:0.5rem;">
        <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
        <a href="{{ route('courses.final-test.questions.edit', [$course, $finalTest, $question]) }}" class="btn btn-secondary">Cancelar</a>
    </div>
</form>
