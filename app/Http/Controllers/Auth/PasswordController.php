<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        // 1. Validasi Input
        // Kita pakai 'validateWithBag' supaya errornya muncul spesifik di form password
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        // 2. Update Password di Database
        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        // 3. Kembali dengan pesan sukses
        return back()->with('status', 'password-updated');
    }
}