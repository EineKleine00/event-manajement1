<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="py-2">
    {{-- HEADER --}}
    <div class="text-center mb-4">
        <h4 class="fw-bold text-body mb-1">Buat Akun Baru</h4>
        <p class="text-secondary small">Bergabunglah sekarang untuk mulai mengelola event Anda.</p>
    </div>

    <form wire:submit="register">
        
        {{-- NAMA LENGKAP --}}
        <div class="form-floating mb-3">
            <input wire:model="name" id="name" type="text" 
                   class="form-control @error('name') is-invalid @enderror" 
                   placeholder="Nama Lengkap" autofocus autocomplete="name">
            <label for="name">Nama Lengkap</label>
            @error('name') <div class="invalid-feedback small">{{ $message }}</div> @enderror
        </div>

        {{-- EMAIL --}}
        <div class="form-floating mb-3">
            <input wire:model="email" id="email" type="email" 
                   class="form-control @error('email') is-invalid @enderror" 
                   placeholder="name@example.com" autocomplete="username">
            <label for="email">Email Address</label>
            @error('email') <div class="invalid-feedback small">{{ $message }}</div> @enderror
        </div>

        {{-- PASSWORD --}}
        <div class="form-floating mb-3">
            <input wire:model="password" id="password" type="password" 
                   class="form-control @error('password') is-invalid @enderror" 
                   placeholder="Password" autocomplete="new-password">
            <label for="password">Password</label>
            @error('password') <div class="invalid-feedback small">{{ $message }}</div> @enderror
        </div>

        {{-- KONFIRMASI PASSWORD --}}
        <div class="form-floating mb-4">
            <input wire:model="password_confirmation" id="password_confirmation" type="password" 
                   class="form-control" 
                   placeholder="Ulangi Password" autocomplete="new-password">
            <label for="password_confirmation">Konfirmasi Password</label>
        </div>

        {{-- TOMBOL REGISTER (LOADING STATE) --}}
        <div class="d-grid mb-4">
            <button type="submit" class="btn btn-primary btn-lg fw-bold shadow-sm d-flex justify-content-center align-items-center gap-2">
                {{-- Teks Normal --}}
                <span wire:loading.remove>
                    Daftar Sekarang <i class="bi bi-person-plus-fill ms-1"></i>
                </span>
                
                {{-- Teks saat Loading --}}
                <span wire:loading>
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Memproses...
                </span>
            </button>
        </div>

        {{-- LINK LOGIN (BALIK KE LOGIN) --}}
        <div class="text-center border-top pt-4">
            <p class="small text-secondary mb-2">Sudah memiliki akun?</p>
            <a href="{{ route('login') }}" wire:navigate class="btn btn-outline-secondary btn-sm fw-bold px-4 rounded-pill">
                Masuk ke Akun Saya
            </a>
        </div>
    </form>
</div>