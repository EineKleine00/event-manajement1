<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    // === FITUR KETUA: List Event ===
    public function index()
    {
        // Menampilkan event yang DIBUAT oleh user login
        $events = Event::where('created_by', Auth::id())
                    ->withCount(['users', 'tasks']) // Hitung total anggota & task
                    ->latest()
                    ->get();
        return view('events.index', compact('events'));
    }

    // === FITUR PETUGAS: List Tugas Saya ===
    public function petugasEvents()
    {
        $user = Auth::user();
        // Ambil event dimana user terdaftar sebagai 'petugas'
        $events = Event::whereHas('users', function ($query) use ($user) {
            $query->where('user_id', $user->id)->where('role', 'petugas');
        })
        ->withCount(['tasks' => function ($q) use ($user) {
             // Hitung task PRIBADI yang belum selesai
             $q->where('user_id', $user->id)->where('is_done', 0);
        }])
        ->latest()->get();

        return view('events.petugas_index', compact('events'));
    }

    // === FITUR SPONSOR: Monitoring ===
    public function sponsorEvents()
    {
        $user = Auth::user();
        // Ambil event dimana user terdaftar sebagai 'sponsor'
        $events = Event::whereHas('users', function ($q) use ($user) {
            $q->where('user_id', $user->id)->where('role', 'sponsor');
        })->latest()->get();

        return view('events.sponsor_index', compact('events'));
    }

    // === CRUD: Create (Hanya Ketua) ===
    public function create() { return view('events.create'); }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required',
            'date' => 'required|date',
        ]);

        // Simpan event, user login otomatis jadi Ketua (created_by)
        Event::create([
            'name' => $request->name,
            'description' => $request->description,
            'date' => $request->date,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('events.index')->with('success', 'Event berhasil dibuat!');
    }

    // === SHOW DETAIL (Dashboard Event) ===
    public function show(Event $event)
    {
        $userId = Auth::id();

        // 1. Cek Security
        $isKetua = $event->created_by == $userId;
        $isMember = $event->users()->where('user_id', $userId)->exists();

        if (!$isKetua && !$isMember) abort(403, 'Akses Ditolak');

        // 2. Ambil Data Tugas (Logika Simpel)
        if ($isKetua) {
            // Ketua lihat semua
            $tasks = $event->tasks()->with('user')->orderBy('created_at', 'desc')->get();
        } else {
            // Petugas lihat punya sendiri
            $tasks = $event->tasks()
                        ->where('user_id', $userId)
                        ->orderBy('created_at', 'desc')
                        ->get();
        }

        // 3. Return View (PERHATIKAN BAGIAN INI)
        // Hapus 'pendingTasks' dan 'completedTasks' dari dalam compact
        return view('events.show', compact('event', 'tasks', 'isKetua')); 
    }

    // === CRUD: Edit & Update (Hanya Ketua) ===
    public function edit(Event $event) 
    { 
        if ($event->created_by !== Auth::id()) abort(403);
        return view('events.edit', compact('event')); 
    }

    public function update(Request $request, Event $event) {
        if ($event->created_by !== Auth::id()) abort(403);
        $event->update($request->validate([
            'name' => 'required', 'description' => 'required', 'date' => 'required|date'
        ]));
        return redirect()->route('events.show', $event)->with('success', 'Event diupdate');
    }

    // === CRUD: Delete (Hanya Ketua) ===
    public function destroy(Event $event) {
        if ($event->created_by !== Auth::id()) abort(403);
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event dihapus');
    }
}