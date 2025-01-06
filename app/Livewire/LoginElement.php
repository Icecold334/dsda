<?php

namespace App\Livewire;

use App\Livewire\Forms\LoginForm;
use Livewire\Component;
use Illuminate\Support\Facades\Session;

class LoginElement extends Component
{

    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function loginCheck(): void
    {
        // dd('sadas');
        // $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        redirect()->to('/dashboard');
        // $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
    public function render()
    {
        return view('auth.login');
    }
}
