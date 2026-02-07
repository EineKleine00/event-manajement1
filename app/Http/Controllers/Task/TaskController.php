<?php

namespace App\Http\Controllers\Task;

use Illuminate\Support\Facades\Storage;
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
        // 1. ketua buat event
        if ($event->created_by !== Auth::id()) abort(403);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string',
            'description' => 'required',
        ]);
        
        // 2. Validasi Ekstra Mencegah salah assign orang luar
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
            'is_done' => 0, 
        ]);

        return back()->with('success', 'Task berhasil diberikan.');
    }

    public function update(Request $request, Task $task)
    {
        // 1. Validasi Input
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'user_id'     => 'required|exists:users,id', // Pastikan user ada
        ]);

        // 2. Update Data
        $task->update([
            'title'       => $request->title,
            'description' => $request->description,
            'user_id'     => $request->user_id,
        ]);

        // 3. Kembali ke halaman sebelumnya
        return back()->with('success', 'Task berhasil diperbarui!');
    }

    // === PETUGAS: Update Status (Checklist) ===
    public function updateStatus(Request $request, Task $task)
    {
        // 1. VALIDASI
        $request->validate([
            // Wajib upload, harus gambar, max 1MB (1024 Kilobytes)
            'image_proof'     => 'required|image|mimes:jpeg,png,jpg|max:1024',
            
            // Catatan boleh kosong, tapi kalau diisi harus berupa teks
            'completion_note' => 'nullable|string|max:500',
        ], [
            'image_proof.max' => 'Ukuran foto tidak boleh lebih dari 1MB!',
            'image_proof.image' => 'File harus berupa gambar (JPG/PNG).',
        ]);

        // 2. PROSES UPLOAD FOTO
        if ($request->hasFile('image_proof')) {
            // Hapus foto lama jika ada (untuk hemat storage kalau re-upload)
            if ($task->image_proof) {
                Storage::disk('public')->delete($task->image_proof);
            }
            
            // Simpan ke folder 'proofs' di storage public
            // Pastikan bagian ini menyimpan path, bukan object file
            $path = $request->file('image_proof')->store('proofs', 'public');
            $task->image_proof = $path; // $path isinya string "proofs/namafile.jpg"
            $task->save();
        }

        // 3. SIMPAN DATA LAIN & UPDATE STATUS
        $task->completion_note = $request->completion_note;
        $task->is_done = true; // Tandai selesai
        $task->save();

        return back()->with('success', 'Tugas berhasil diselesaikan! Bukti & Catatan tersimpan.');
    }

    // === KETUA: Hapus Task ===
    public function destroy(Task $task) {
        if ($task->event->created_by !== Auth::id()) abort(403);
        $task->delete();
        return back()->with('success', 'Task dihapus.');
    }
}