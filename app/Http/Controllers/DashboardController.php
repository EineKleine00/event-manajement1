<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // --- STATISTIK (ANGKA) ---
        $ketuaCount = Event::where('created_by', $userId)->count();
        $petugasTaskCount = Task::where('user_id', $userId)->where('is_done', 0)->count();
        $sponsorCount = Event::whereHas('users', function ($q) use ($userId) {
            $q->where('user_id', $userId)->where('role', 'sponsor');
        })->count();

        // --- DATA REAL (UNTUK TABEL/LIST DASHBOARD) ---
        
        // 1. 5 Event Terdekat (Upcoming)
        // Mengambil event dimana user terlibat (sebagai ketua/petugas/sponsor)
        $upcomingEvents = Event::where('created_by', $userId)
            ->orWhereHas('users', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->whereDate('date', '>=', now()) // Hanya event masa depan/hari ini
            ->orderBy('date', 'asc')
            ->limit(5)
            ->get();

        // 2. 5 Task Prioritas (Pending)
        $priorityTasks = Task::where('user_id', $userId)
            ->where('is_done', 0)
            ->with('event')
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'ketuaCount', 'petugasTaskCount', 'sponsorCount', 
            'upcomingEvents', 'priorityTasks'
        ));
    }
}