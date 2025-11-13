@extends('layouts.app')

@section('title', 'Questões do teste final')

@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
        <div>
            <p style="margin:0; font-size:0.85rem; color:#94a3b8;">{{ $course->title }}</p>
            <h1 style="margin:0;">Teste final · Questões</h1>
        </div>
        <a href="{{ route('courses.final-test.questions.create', [$course, $finalTest]) }}" class="btn btn-primary">Nova questão</a>
    </div>

    <div class="card" style="margin-top:1rem;">
        @forelse ($finalTest->questions as $question)
            <article style="padding-bottom:1rem; border-bottom:1px solid #e2e8f0; margin-bottom:1rem;">
                <header style="display:flex; justify-content:space-between; gap:1rem;">
                    <div>
                        <p style="margin:0; font-size:0.85rem; color:#94a3b8;">Questão {{ $question->position }}</p>
                        <strong>{{ $question->title }}</strong>
                    </div>
                    <div>
                        <a href="{{ route('courses.final-test.questions.edit', [$course, $finalTest, $question]) }}" class="btn btn-secondary" style="font-size:0.85rem;">Editar</a>
                    </div>
                </header>
                @if ($question->statement)
                    <p style="margin:0.5rem 0 0 0;">{{ $question->statement }}</p>
                @endif
                <ul style="margin:0.75rem 0 0 0; padding-left:1rem;">
                    @foreach ($question->options as $option)
                        <li>
                            {{ $option->label }}
                            @if ($option->is_correct)
                                <span style="color:#22c55e; font-weight:600;">(correta)</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </article>
        @empty
            <p style="margin:0;">Nenhuma questão cadastrada.</p>
        @endforelse
    </div>
@endsection
