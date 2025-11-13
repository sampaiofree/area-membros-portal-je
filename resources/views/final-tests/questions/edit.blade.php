@extends('layouts.app')

@section('title', 'Editar questão')

@section('content')
    <h1 style="margin-top:0;">Editar questão · {{ $question->title }}</h1>

    @include('final-tests.questions.partials.form', [
        'action' => route('courses.final-test.questions.update', [$course, $finalTest, $question]),
        'method' => 'PUT',
        'submitLabel' => 'Salvar questão',
        'question' => $question,
        'course' => $course,
        'finalTest' => $finalTest,
    ])

    <section style="margin-top:2rem;">
        <header style="display:flex; justify-content:space-between; align-items:center;">
            <h2 style="margin:0;">Alternativas</h2>
            <a href="{{ route('courses.final-test.questions.options.create', [$course, $finalTest, $question]) }}" class="btn btn-secondary">Nova opção</a>
        </header>

        <div class="card" style="margin-top:1rem;">
            <ul style="margin:0; padding:0; list-style:none;">
                @forelse ($question->options as $option)
                    <li style="padding:0.75rem 0; border-bottom:1px solid #e2e8f0; display:flex; justify-content:space-between; gap:0.5rem; flex-wrap:wrap;">
                        <div>
                            <strong>{{ $option->label }}</strong>
                            @if ($option->is_correct)
                                <span style="color:#22c55e; margin-left:0.5rem;">(correta)</span>
                            @endif
                        </div>
                        <div style="display:flex; gap:0.25rem;">
                            <a href="{{ route('courses.final-test.questions.options.edit', [$course, $finalTest, $question, $option]) }}" class="btn btn-secondary" style="font-size:0.85rem;">Editar</a>
                            <form method="POST" action="{{ route('courses.final-test.questions.options.destroy', [$course, $finalTest, $question, $option]) }}" onsubmit="return confirm('Remover esta alternativa?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="font-size:0.85rem;">Excluir</button>
                            </form>
                        </div>
                    </li>
                @empty
                    <li>Nenhuma opção cadastrada.</li>
                @endforelse
            </ul>
        </div>
    </section>
@endsection
