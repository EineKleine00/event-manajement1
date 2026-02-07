<?php

namespace App\Http\Controllers\Admin; 

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminEventController extends Controller
{
    /**
     * Menampilkan SEMUA event (Punya siapa saja)
     */
    public function index(Request $request)
    {
        // 1. Query Dasar: Ambil Event + User (termasuk yang soft delete)
        $query = Event::with(['user' => function ($q) {
            $q->withTrashed(); 
        }]);

        // 2. Fitur Pencarian (Search)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('location', 'LIKE', "%{$search}%")
                  // Cari juga berdasarkan nama User pembuatnya
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        // 3. Filter Status (Upcoming / Selesai)
        if ($request->has('status') && $request->status != '') {
            if ($request->status == 'upcoming') {
                $query->where('date', '>=', now());
            } elseif ($request->status == 'past') {
                $query->where('date', '<', now());
            }
        }

        // 4. Ambil Data (Pagination 10)
        $events = $query->latest()->paginate(10);

        // Arahkan ke view khusus admin
        return view('admin.event.index', compact('events'));
    }

    /**
     * Form Edit (Admin bisa edit punya siapa aja)
     */
    public function edit($id)
    {
        // Cari event, kalau terhapus juga bisa diedit (opsional, di sini kita cari yg aktif aja)
        $event = Event::findOrFail($id);
        
        // Kita "Nebeng" view edit punya user biasa biar hemat file
        return view('events.edit', compact('event')); 
    }

    /**
     * Proses Update
     */
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'event_date' => 'required|date',
            'location' => 'required|string',
            'description' => 'nullable|string',
        ]);

        // Update tanpa cek user_id (karena ini Admin)
        $event->update($request->all());

        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil diperbarui oleh Admin.');
    }

    /**
     * Hapus Event (Soft Delete)
     */
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil dihapus.');
    }
}