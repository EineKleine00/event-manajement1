<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Event\EventController;
use App\Http\Controllers\Event\EventMemberController; // Controller Lama (Tetap Dipakai)
use App\Http\Controllers\Event\EventReportController; // Controller Lama (Tetap Dipakai)
use App\Http\Controllers\Task\TaskController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// GROUP ROUTE: Wajib Login
Route::middleware(['auth', 'verified'])->group(function () {

    // ====================================================
    // 1. DASHBOARD UTAMA (Pintu Masuk)
    // ====================================================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ====================================================
    // 2. EVENT MANAGEMENT (Fitur Ketua)
    // ====================================================
    // Menghandle: index, create, store, show, edit, update, destroy
    Route::resource('events', EventController::class);

    // ====================================================
    // 3. MEMBER MANAGEMENT (Fitur Ketua - Tambah Anggota)
    // ====================================================
    // Diambil dari kode lama, dirapikan group-nya
    Route::controller(EventMemberController::class)->group(function () {
        // Lihat daftar member (biasanya di-include di show event, tapi ini route khususnya)
        Route::get('events/{event}/members', 'index')->name('events.members.index');
        
        // Tambah Member Baru
        Route::post('events/{event}/members', 'store')->name('events.members.store');
        
        // Hapus Member
        Route::delete('events/{event}/members/{user}', 'destroy')->name('events.members.destroy');
    });

    // ====================================================
    // 4. TASK MANAGEMENT (Gabungan Ketua & Petugas)
    // ====================================================
    Route::controller(TaskController::class)->group(function () {
        // Ketua: Assign Task ke Petugas
        Route::post('/events/{event}/tasks', 'store')->name('tasks.store');
        
        // Ketua: Edit & Hapus Task
        Route::put('/tasks/{task}', 'update')->name('tasks.update');
        Route::delete('/tasks/{task}', 'destroy')->name('tasks.destroy');
        
        // Petugas: Update Status (Checklist) - Menggantikan 'tasks.done' lama
        Route::patch('/tasks/{task}/status', 'updateStatus')->name('tasks.update-status');
    });

    // ====================================================
    // 5. PORTAL KHUSUS (Pengganti Group 'petugas' & 'sponsor' lama)
    // ====================================================
    Route::controller(EventController::class)->prefix('portal')->name('portal.')->group(function () {
        Route::get('/petugas', 'petugasEvents')->name('petugas'); // Portal Petugas
        Route::get('/sponsor', 'sponsorEvents')->name('sponsor'); // Portal Sponsor
    });

    // ====================================================
    // 6. REPORT SYSTEM (Fitur Lama Tetap Ada)
    // ====================================================
    Route::controller(EventReportController::class)->prefix('events/{event}/report')->name('events.report')->group(function () {
        Route::get('/', 'show');      // View Laporan HTML (route('events.report'))
        Route::get('/pdf', 'pdf')->name('.pdf'); // Export PDF (route('events.report.pdf'))
    });

    // ====================================================
    // 7. LIVE SEARCH (Untuk Tambah Member)
    // ====================================================
    Route::get('/ajax/users/search', function (Request $request) {
        $q = $request->q;
        return \App\Models\User::where('name', 'like', "%$q%")
            ->orWhere('email', 'like', "%$q%") // Tambah search by email juga biar enak
            ->limit(5)
            ->get(['id', 'name', 'email']);
    })->name('ajax.users.search');

    // ====================================================
    // 8. PROFILE & LOGOUT
    // ====================================================
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    // Manual Logout Route (Pencegah error route logout not defined)
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    })->name('logout');

});

require __DIR__.'/auth.php';