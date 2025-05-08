<?php

namespace Liamtseva\Cinema\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public $email = '';

    public $password = '';

    public $remember = false;

    protected $rules = [
        'email' => 'required|email|exists:users,email',
        'password' => 'required',
    ];

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->flash('status', 'Успішний вхід!');

            return $this->redirect(route('home'), navigate: true);
        }
        $this->addError('password', 'Невірний пароль.');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
