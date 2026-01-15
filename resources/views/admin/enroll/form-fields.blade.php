@php
    $enrollment = $enrollment ?? null;
@endphp

<div class="grid gap-4 md:grid-cols-2">
    <label class="space-y-2 text-sm font-semibold text-slate-600">
        <span>Curso</span>
        <select
            name="course_id"
            required
            class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30"
        >
            <option value="">Selecione um curso</option>
            @foreach ($courses as $course)
                <option value="{{ $course->id }}" @selected((string) old('course_id', $enrollment?->course_id) === (string) $course->id)>
                    #{{ $course->id }} - {{ $course->title }}
                </option>
            @endforeach
        </select>
        @error('course_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
    </label>

    <label class="space-y-2 text-sm font-semibold text-slate-600">
        <span>Aluno</span>
        <select
            name="user_id"
            required
            class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30"
        >
            <option value="">Selecione um aluno</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}" @selected((string) old('user_id', $enrollment?->user_id) === (string) $user->id)>
                    #{{ $user->id }} - {{ $user->preferredName() }} ({{ $user->email ?? $user->whatsapp ?? 'Sem contato' }})
                </option>
            @endforeach
        </select>
        @error('user_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
    </label>
</div>

<div class="grid gap-4 md:grid-cols-2">
    <label class="space-y-2 text-sm font-semibold text-slate-600">
        <span>Progresso (%)</span>
        <input
            type="number"
            min="0"
            max="100"
            name="progress_percent"
            required
            value="{{ old('progress_percent', $enrollment?->progress_percent ?? 0) }}"
            class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30"
        >
        @error('progress_percent') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
    </label>

    <label class="space-y-2 text-sm font-semibold text-slate-600">
        <span>Concluido em</span>
        <input
            type="datetime-local"
            name="completed_at"
            value="{{ old('completed_at', $enrollment?->completed_at?->format('Y-m-d\\TH:i')) }}"
            class="w-full rounded-xl border border-edux-line px-4 py-3 focus:border-edux-primary focus:ring-edux-primary/30"
        >
        @error('completed_at') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
    </label>
</div>
