<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventMemberController extends Controller
{
    /**
     * Tambah Member Baru ke Event
     */
    public function store(Request $request, Event $event)
    {
        // 1. Cek Hak Akses (Hanya Ketua yang boleh tambah)
        if ($event->created_by !== Auth::id()) {
            abort(403, 'Anda bukan ketua event ini.');
        }

        // 2. Validasi Input
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role'    => 'required|in:petugas,sponsor',
        ]);

        // 3. Cek apakah user sudah ada di event ini?
        $exists = $event->users()->where('user_id', $request->user_id)->exists();
        if ($exists) {
            return back()->withErrors(['User tersebut sudah terdaftar di event ini.']);
        }

        // 4. Simpan ke Tabel Pivot (event_user)
        $event->users()->attach($request->user_id, ['role' => $request->role]);

        return back()->with('success', 'Anggota berhasil ditambahkan.');
    }

    /**
     * Hapus Member dari Event
     */
    public function destroy(Event $event, User $user)
    {
        // 1. Cek Hak Akses
        if ($event->created_by !== Auth::id()) {
            abort(403);
        }

        // 2. Hapus dari tabel pivot
        $event->users()->detach($user->id);

        return back()->with('success', 'Anggota berhasil dihapus dari event.');
    }
}