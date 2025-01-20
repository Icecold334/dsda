<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public function mount()
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard'));

            return;
        }
    }
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        Auth::user()->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/');
    }
}; ?>

<div class="h-full flex items-center ">
    @push('vite')
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endpush
    <div>
        <h1 class="text-4xl mb-4  font-medium">Selamat Datang <span class="font-bold">{{ auth()->user()->name }}!</span>
        </h1>

        <p class="text-xl mb-4">
            Anda berhasil login. Namun, akun Anda belum diverifikasi. Harap menunggu verifikasi dari pengguna yang
            memiliki
            hak akses yang diperlukan. Anda akan diberitahu segera setelah akun Anda diverifikasi dan semua fitur sistem
            akan sepenuhnya tersedia untuk Anda.


        </p>
        <p class="text-xl mb-4">
            Terima kasih atas kesabaran Anda.
        </p>

        <button class="sign-btn max-w-32" wire:click="logout">Keluar</button>

    </div>
</div>

{{-- <div>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <x-primary-button wire:click="sendVerification">
            {{ __('Resend Verification Email') }}
        </x-primary-button>

        <button wire:click="logout" type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            {{ __('Log Out') }}
        </button>
    </div>
</div> --}}
