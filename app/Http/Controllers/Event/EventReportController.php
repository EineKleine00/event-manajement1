<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class EventReportController extends Controller
{
    /**
     * Tampilkan Laporan (Mode Cetak)
     */
    public function show(Event $event)
    {
        // 1. Validasi Akses (Ketua, Petugas, Sponsor boleh lihat)
        $userId = Auth::id();
        $isKetua = $event->created_by == $userId;
        $isMember = $event->users()->where('user_id', $userId)->exists();

        if (!$isKetua && !$isMember) {
            abort(403, 'Akses Ditolak');
        }

        // 2. Load Data Lengkap
        $event->load(['users', 'tasks.user']);

        // 3. Hitung Statistik
        $totalTasks = $event->tasks->count();
        $completedTasks = $event->tasks->where('is_done', 1)->count();
        $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        return view('events.report', compact('event', 'progress', 'totalTasks', 'completedTasks'));
    }

    /**
     * (Opsional) Jika nanti mau pakai PDF library
     */
    public function pdf(Event $event)
    {
        // Untuk sekarang redirect ke show aja biar diprint dari browser
        return redirect()->route('events.report', $event->id);
    }
}