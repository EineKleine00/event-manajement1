<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf; // <--- 1. WAJIB IMPORT INI

class EventReportController extends Controller
{
    // Fungsi Private untuk Validasi & Hitung Data
    private function getData($event)
    {
        // 1. Validasi Akses
        $userId = Auth::id();
        $isKetua = $event->created_by == $userId;
        $isMember = $event->users()->where('user_id', $userId)->exists();

        // Admin juga boleh lihat (Opsional, buat jaga-jaga)
        $isAdmin = Auth::user()->user_role === 'admin'; 

        if (!$isKetua && !$isMember && !$isAdmin) {
            abort(403, 'Akses Ditolak');
        }

        // 2. Load Data Lengkap
        $event->load(['users', 'tasks.user']);

        // 3. Hitung Statistik
        $totalTasks = $event->tasks->count();
        $completedTasks = $event->tasks->where('is_done', 1)->count();
        $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
        $totalMembers = $event->users->count();

        return compact('event', 'progress', 'totalTasks', 'completedTasks', 'totalMembers');
    }

    public function show(Event $event)
    {
        $data = $this->getData($event);
        return view('events.report', $data); // Tampilan Web HTML
    }

    public function pdf(Event $event)
    {
        // 1. Apakah dia SUPER ADMIN? -> BOLEH
        $isAdmin = (Auth::user()->user_role === 'admin');

        // 2. Apakah dia KETUA (Pemilik Event)? -> BOLEH
        $isKetua = ($event->created_by === Auth::id());

        // 3. JIKA BUKAN KEDUANYA -> TENDANG KELUAR (403 Forbidden)
        if (! $isAdmin && ! $isKetua) {
            abort(403, 'Akses Ditolak. Laporan hanya untuk Ketua Panitia & Admin.');
        }
        $data = $this->getData($event);

        // 2. GENERATE PDF MENGGUNAKAN DOMPDF
        $pdf = Pdf::loadView('events.report', $data);
        
        // 3. Set Ukuran Kertas (Opsional)
        $pdf->setPaper('A4', 'portrait');

        // 4. Download File
        $filename = 'Laporan_' . str_replace(' ', '_', $event->name) . '.pdf';
        
        return $pdf->download($filename);
    }
}