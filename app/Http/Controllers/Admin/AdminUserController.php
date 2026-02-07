<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query(); // Tidak perlu select spesifik lagi karena nama kolom beda

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        
        // Filter role (jika ada) pakai kolom baru
        // $query->where('user_role', 'admin'); 

        $users = $query->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:admin,user', // Nama input form tetap 'role' gpp
            'password' => 'nullable|min:8',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            
            // SIMPAN KE KOLOM BARU 'user_role'
            'user_role' => $validated['role'], 
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }
}