<?php

namespace App\Livewire\Admin;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class ProfessorForm extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function save(): void
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $data['name'],
            'display_name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => UserRole::TEACHER->value,
        ]);

        session()->flash('status', 'Professor cadastrado com sucesso.');

        $this->reset(['name', 'email', 'password', 'password_confirmation']);
        $this->dispatch('professor-created');
    }

    public function render()
    {
        return view('livewire.admin.professor-form');
    }
}
