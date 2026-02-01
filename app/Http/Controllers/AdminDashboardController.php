<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil Statistik Utama
        $stats = [
            'total_users' => User::count(),
            'total_admins' => User::where('user_role', 'admin')->count(), // Pakai user_role
            'total_events' => Event::count(),
            'upcoming_events' => Event::where('date', '>=', now())->count(),
        ];

        // 2. Ambil 5 User Terbaru (untuk tabel preview)
        $latestUsers = User::latest()->limit(5)->get();

        // 3. Ambil 5 Event Terbaru
        $latestEvents = Event::latest()->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'latestUsers', 'latestEvents'));
    }
}