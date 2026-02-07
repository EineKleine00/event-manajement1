<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Event;
use App\Http\Controllers\Controller;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. Hitung Statistik Utama
        $stats = [
            'total_users'     => User::count(),
            'total_admins'    => User::where('user_role', 'admin')->count(), // Sesuaikan nama kolom role kamu
            'total_events'    => Event::count(),
            'upcoming_events' => Event::where('date', '>=', now())->count(),
        ];

        // 2. Ambil 5 User Terakhir Daftar
        $latestUsers = User::latest()->take(5)->get();

        // 3. Ambil 5 Event Terakhir Dibuat
        $latestEvents = Event::latest()->take(5)->get();

        // 4. Kirim semua data ke View
        return view('admin.dashboard', compact('stats', 'latestUsers', 'latestEvents'));
    }
}