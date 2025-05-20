<?php

namespace Liamtseva\Cinema\Livewire\Auth;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Liamtseva\Cinema\Models\User;
use Livewire\Component;

class Register extends Component
{
    public $name = '';

    public $email = '';

    public $password = '';

    public $password_confirmation = '';

    protected $rules = [
        'name' => 'required|string|max:255|unique:users,name|min:3',
        'email' => 'required|email|unique:users,email|max:255|min:3',
        'password' => 'required|min:8|max:32',
    ];

    public function register()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        event(new Registered($user));
        Auth::login($user);

        session()->flash('status', 'Реєстрація успішна! Будь ласка, підтвердіть вашу пошту.');

        return $this->redirectRoute('verification.notice');
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
