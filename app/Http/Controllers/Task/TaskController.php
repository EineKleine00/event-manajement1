<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    // === KETUA: Buat Task Baru & Assign ke Petugas ===
    public function store(Request $request, Event $event)
    {
        // 1. Pastikan yang bikin task adalah Ketua Event
        if ($event->created_by !== Auth::id()) abort(403);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string',
            'description' => 'required',
        ]);
        
        // 2. Validasi Ekstra: Pastikan user yang ditunjuk ADALAH PETUGAS di event ini
        // (Mencegah salah assign orang luar)
        $isPetugas = DB::table('event_user')
            ->where('event_id', $event->id)
            ->where('user_id', $request->user_id)
            ->where('role', 'petugas')
            ->exists();

        if (!$isPetugas) return back()->with('error', 'User tersebut bukan petugas di event ini.');

        // 3. Buat Task
        $event->tasks()->create([
            'user_id' => $validated['user_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'is_done' => 0, // Default belum selesai
        ]);

        return back()->with('success', 'Task berhasil diberikan.');
    }

    // === PETUGAS: Update Status (Checklist) ===
    public function updateStatus(Request $request, Task $task)
    {
        // Pastikan yang update adalah pemilik task itu sendiri
        if ($task->user_id !== Auth::id()) abort(403);

        // Jika input 'completed', set 1. Jika tidak, set 0.
        $task->update(['is_done' => $request->status === 'completed' ? 1 : 0]);
        
        return back()->with('success', 'Status task diperbarui.');
    }
    
    // === KETUA: Hapus Task ===
    public function destroy(Task $task) {
        if ($task->event->created_by !== Auth::id()) abort(403);
        $task->delete();
        return back()->with('success', 'Task dihapus.');
    }
}