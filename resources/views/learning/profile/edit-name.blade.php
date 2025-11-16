@extends('layouts.student')

@section('title', 'Atualizar nome')

@section('content')
    <h1 style="margin-top:0;">Atualizar nome</h1>
    <p style="margin-top:0; color:#475569;">
        Use esta opção apenas se o seu nome estiver incorreto nos certificados. A alteração só pode ser feita uma vez.
    </p>

    @if (! $user->name_change_available)
        <div class="card" style="background:#fee2e2; color:#b91c1c;">
            Você já utilizou a alteração de nome.
        </div>
    @else
        <form method="POST" action="{{ route('learning.profile.name.update') }}" class="card" style="display:flex; flex-direction:column; gap:1rem; max-width:420px;">
            @csrf
            @method('PUT')
            <label style="display:flex; flex-direction:column; gap:0.25rem;">
                <span>Nome completo</span>
                <input type="text" name="display_name" value="{{ old('display_name', $user->display_name ?? $user->name) }}" required style="padding:0.75rem; border-radius:8px; border:1px solid #cbd5f5;">
            </label>

            <button type="submit" class="btn btn-primary">Salvar nome</button>
        </form>
    @endif
@endsection
