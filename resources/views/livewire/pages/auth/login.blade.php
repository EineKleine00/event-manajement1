<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        // --- PERBAIKAN DI SINI ---
        
        // Ambil user dengan aman
        $user = Auth::user();

        // Cek dulu: Apakah user benar-benar ada?
        if ($user) {
            // Gunakan '?->' agar kalau error, dia tidak crash
            $role = $user->user_role; 

            if ($role === 'admin') {
                $this->redirect(route('admin.dashboard', absolute: false), navigate: true);
            } else {
                $this->redirect(route('dashboard', absolute: false), navigate: true);
            }
        } else {
            // Kalau user null (login gagal tapi gak error), lempar balik
            $this->redirect('/login', navigate: true);
        }
    }
}; ?>

<div class="py-2">
    {{-- HEADER --}}
    <div class="text-center mb-4">
        <h4 class="fw-bold text-body mb-1">Welcome Back!</h4>
        <p class="text-secondary small">Silakan login untuk mengakses akun Anda.</p>
    </div>

    {{-- SESSION STATUS --}}
    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{-- FORM LIVEWIRE --}}
    <form wire:submit="login">
        
        {{-- EMAIL --}}
        <div class="form-floating mb-3">
            <input wire:model="form.email" id="email" type="email" 
                   class="form-control @error('form.email') is-invalid @enderror" 
                   placeholder="name@example.com" autofocus autocomplete="username">
            <label for="email">Email Address</label>
            @error('form.email') <div class="invalid-feedback small">{{ $message }}</div> @enderror
        </div>

        {{-- PASSWORD --}}
        <div class="form-floating mb-3">
            <input wire:model="form.password" id="password" type="password" 
                   class="form-control @error('form.password') is-invalid @enderror" 
                   placeholder="Password" autocomplete="current-password">
            <label for="password">Password</label>
            @error('form.password') <div class="invalid-feedback small">{{ $message }}</div> @enderror
        </div>

        {{-- REMEMBER & FORGOT --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input wire:model="form.remember" id="remember" type="checkbox" class="form-check-input">
                <label for="remember" class="form-check-label small text-secondary">Ingat Saya</label>
            </div>

            @if (Route::has('password.request'))
                <a class="small text-primary fw-bold text-decoration-none" href="{{ route('password.request') }}" wire:navigate>
                    Lupa Password?
                </a>
            @endif
        </div>

        {{-- TOMBOL LOGIN (LOADING STATE) --}}
        <div class="d-grid mb-4">
            <button type="submit" class="btn btn-primary btn-lg fw-bold shadow-sm d-flex justify-content-center align-items-center gap-2">
                
                {{-- Teks Normal --}}
                <span wire:loading.remove>Masuk Sekarang <i class="bi bi-box-arrow-in-right"></i></span>
                
                {{-- Teks saat Loading --}}
                <span wire:loading>
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Loading...
                </span>
            </button>
        </div>

        {{-- LINK REGISTER (LIVEWIRE NAVIGATE) --}}
        <div class="text-center border-top pt-4">
            <p class="small text-secondary mb-2">Belum punya akun?</p>
            <a href="{{ route('register') }}" wire:navigate class="btn btn-outline-secondary btn-sm fw-bold px-4 rounded-pill">
                Daftar Akun Baru
            </a>
        </div>
    </form>
</div>