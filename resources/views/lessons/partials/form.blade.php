<form method="POST" action="{{ $action }}" class="card" style="display:flex; flex-direction:column; gap:1rem; max-width:650px;">
    @csrf
    @if (! in_array($method, ['POST', null], true))
        @method($method)
    @endif

    <label style="display:flex; flex-direction:column; gap:0.25rem;">
        <span>Título da aula</span>
        <input type="text" name="title" value="{{ old('title', $lesson->title) }}" required style="padding:0.75rem; border-radius:8px; border:1px solid #cbd5f5;">
    </label>

    <label style="display:flex; flex-direction:column; gap:0.25rem;">
        <span>Conteúdo</span>
        <textarea name="content" rows="5" style="padding:0.75rem; border-radius:8px; border:1px solid #cbd5f5;">{{ old('content', $lesson->content) }}</textarea>
    </label>

    <label style="display:flex; flex-direction:column; gap:0.25rem;">
        <span>Link de vídeo</span>
        <input type="url" name="video_url" value="{{ old('video_url', $lesson->video_url) }}" style="padding:0.75rem; border-radius:8px; border:1px solid #cbd5f5;">
    </label>

    <label style="display:flex; flex-direction:column; gap:0.25rem;">
        <span>Duração (minutos)</span>
        <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $lesson->duration_minutes) }}" min="1" style="padding:0.75rem; border-radius:8px; border:1px solid #cbd5f5;">
    </label>

    <label style="display:flex; flex-direction:column; gap:0.25rem;">
        <span>Posição</span>
        <input type="number" name="position" value="{{ old('position', $lesson->position) }}" min="1" style="padding:0.75rem; border-radius:8px; border:1px solid #cbd5f5;">
    </label>

    <div style="display:flex; gap:0.5rem;">
        <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
        <a href="{{ route('courses.edit', $course) }}" class="btn btn-secondary">Cancelar</a>
    </div>
</form>
