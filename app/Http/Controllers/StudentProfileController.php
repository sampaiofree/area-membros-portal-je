<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentProfileController extends Controller
{
    public function editName(Request $request): View
    {
        $user = $request->user();

        return view('learning.profile.edit-name', compact('user'));
    }

    public function updateName(Request $request): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user->name_change_available, 403, 'A alteração de nome já foi utilizada.');

        $validated = $request->validate([
            'display_name' => ['required', 'string', 'max:255'],
        ]);

        $user->forceFill([
            'name' => $validated['display_name'],
            'display_name' => $validated['display_name'],
            'name_change_available' => false,
        ])->save();

        return redirect()->route('learning.profile.name.edit')->with('status', 'Nome atualizado com sucesso.');
    }
}
