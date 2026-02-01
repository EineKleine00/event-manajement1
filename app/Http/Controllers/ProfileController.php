<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request; // Kita pakai Request standar
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Validation\Rule; // Penting untuk cek email unik

class ProfileController extends Controller
{
    /**
     * Tampilkan formulir profil user.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update informasi profil user (Nama & Email).
     */
    public function update(Request $request): RedirectResponse
    {
        // 1. Validasi Langsung di Sini (Tanpa File Request Terpisah)
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'string', 
                'lowercase', 
                'email', 
                'max:255', 
                // Cek email unik di tabel 'users', tapi abaikan punya diri sendiri
                Rule::unique('users')->ignore($request->user()->id),
            ],
        ]);

        // 2. Isi data baru ke user
        $request->user()->fill($validated);

        // 3. Jika email berubah, reset status verifikasi (Opsional, bawaan Laravel)
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        // 4. Simpan
        $request->user()->save();

        // 5. Balik ke halaman edit dengan pesan sukses
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Hapus akun user.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Validasi Password sebelum hapus
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}