@extends('layouts.student')

@section('title', 'Responder teste final')

@section('content')
    <h1 style="margin-top:0;">Responder teste final</h1>
    <p style="margin-top:0; color:#475569;">{{ $finalTest->title }}</p>

    @if ($remainingSeconds !== null)
        <div class="card" style="margin-bottom:1rem; display:flex; justify-content:space-between; align-items:center;">
            <strong>Tempo restante: <span data-countdown>{{ gmdate('i:s', $remainingSeconds) }}</span></strong>
            <small style="color:#64748b;">O teste será enviado automaticamente quando o tempo acabar.</small>
        </div>
    @endif

    <form method="POST" action="{{ route('learning.courses.final-test.submit', [$course, $attempt]) }}" class="card" style="display:flex; flex-direction:column; gap:1.5rem;">
        @csrf
        @foreach ($finalTest->questions as $index => $question)
            <article>
                <p style="margin:0; color:#94a3b8;">Questão {{ $question->position ?? $index + 1 }}</p>
                <strong>{{ $question->title }}</strong>
                @if ($question->statement)
                    <p style="margin:0.5rem 0 0 0;">{{ $question->statement }}</p>
                @endif

                <ul style="list-style:none; margin:0.75rem 0 0 0; padding:0;">
                    @foreach ($question->options as $option)
                        <li style="margin-bottom:0.5rem;">
                            <label style="display:flex; gap:0.5rem; align-items:center; padding:0.5rem; border-radius:8px; border:1px solid #e2e8f0;">
                                <input type="radio" name="question_{{ $question->id }}" value="{{ $option->id }}" required>
                                <span>{{ $option->label }}</span>
                            </label>
                        </li>
                    @endforeach
                </ul>
            </article>
        @endforeach

        <button type="submit" class="btn btn-primary">Enviar respostas</button>
    </form>

    <script>
        const countdownEl = document.querySelector('[data-countdown]');
        const remainingSeconds = {{ $remainingSeconds ?? 'null' }};

        if (countdownEl && remainingSeconds) {
            let seconds = remainingSeconds;
            const interval = setInterval(() => {
                if (seconds <= 0) {
                    clearInterval(interval);
                    countdownEl.textContent = '00:00';
                    document.querySelector('form').submit();
                    return;
                }

                seconds--;
                const minutes = String(Math.floor(seconds / 60)).padStart(2, '0');
                const secs = String(seconds % 60).padStart(2, '0');
                countdownEl.textContent = `${minutes}:${secs}`;
            }, 1000);
        }
    </script>
@endsection
