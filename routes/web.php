<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Event\EventController;
use App\Http\Controllers\Event\EventMemberController; 
use App\Http\Controllers\Event\EventReportController; 
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminSettingController;
use App\Http\Controllers\Task\TaskController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\PasswordController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root ke dashboard (nanti middleware akan mengarahkan sesuai role)
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// GROUP UTAMA: WAJIB LOGIN (Auth & Verified)
Route::middleware(['auth', 'verified'])->group(function () {

    // ====================================================
    // A. SHARED ROUTES (Bisa Diakses Admin & User)
    // ====================================================
    
    // 1. Profile
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    // 2. Ganti Password
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    // 3. Logout
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    })->name('logout');


   // ====================================================
    // B. AREA KHUSUS SUPER ADMIN
    // ====================================================
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        
        // Dashboard & Users (Yang sudah ada biarkan saja)
        Route::get('/dashboard', [\App\Http\Controllers\AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::resource('users', AdminUserController::class)->only(['index', 'update', 'destroy']);

        // --- TAMBAHKAN INI ---
        // Route Settings
        Route::controller(\App\Http\Controllers\AdminSettingController::class)->group(function() {
            Route::get('/settings', 'index')->name('admin.settings.index');
            Route::put('/settings', 'update')->name('admin.settings.update');
        });

    });


    // ====================================================
    // C. AREA KHUSUS USER BIASA (Event Organizer)
    // ====================================================
    Route::middleware(['role:user'])->group(function () {

        // 1. Dashboard Utama User
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // 2. Event Management
        Route::resource('events', EventController::class);

        // 3. Member Management
        Route::controller(EventMemberController::class)->group(function () {
            Route::get('events/{event}/members', 'index')->name('events.members.index');
            Route::post('events/{event}/members', 'store')->name('events.members.store');
            Route::delete('events/{event}/members/{user}', 'destroy')->name('events.members.destroy');
        });

        // 4. Task Management
        Route::controller(TaskController::class)->group(function () {
            Route::post('/events/{event}/tasks', 'store')->name('tasks.store');
            Route::put('/tasks/{task}', 'update')->name('tasks.update');
            Route::delete('/tasks/{task}', 'destroy')->name('tasks.destroy');
            Route::patch('/tasks/{task}/status', 'updateStatus')->name('tasks.update-status');
        });

        // 5. Portal (Petugas & Sponsor)
        Route::controller(EventController::class)->prefix('portal')->name('portal.')->group(function () {
            Route::get('/petugas', 'petugasEvents')->name('petugas');
            Route::get('/sponsor', 'sponsorEvents')->name('sponsor');
        });

        // 6. Report System
        Route::controller(EventReportController::class)->prefix('events/{event}/report')->name('events.report')->group(function () {
            Route::get('/', 'show');
            Route::get('/pdf', 'pdf')->name('.pdf');
        });

        // 7. Live Search (Khusus User mencari Anggota)
        Route::get('/ajax/users/search', function (Request $request) {
            $q = $request->q;
            $eventId = $request->event_id;

            $existingUserIds = [];
            if($eventId) {
                $existingUserIds = DB::table('event_user')
                                    ->where('event_id', $eventId)
                                    ->pluck('user_id')
                                    ->toArray();
            }

            return \App\Models\User::query()
                ->where('role', '!=', 'admin') // Opsional: Jangan tampilkan admin di pencarian member event
                ->where('id', '!=', auth()->id())
                ->whereNotIn('id', $existingUserIds)
                ->where(function($query) use ($q) {
                    $query->where('name', 'like', "%$q%")
                        ->orWhere('email', 'like', "%$q%");
                })
                ->limit(5)
                ->get(['id', 'name', 'email']);

        })->name('ajax.users.search');
    });

});

require __DIR__.'/auth.php';