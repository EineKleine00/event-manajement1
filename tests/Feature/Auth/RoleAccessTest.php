<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function tamu_tidak_bisa_akses_dashboard_admin()
    {
        // Tamu (Belum login) coba akses admin
        $response = $this->get(route('admin.dashboard'));

        // Harus dilempar ke halaman Login
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function user_biasa_DIALIHKAN_jika_masuk_halaman_admin()
    {
        // 1. Login sebagai User Biasa
        $user = User::factory()->create(['user_role' => 'user']);

        // 2. Coba paksa masuk halaman Admin
        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        // 3. LOGIKA BARU: Dilempar balik ke dashboard user (Bukan error 403)
        $response->assertRedirect(route('dashboard'));
    }

    /** @test */
    public function admin_BOLEH_masuk_halaman_admin()
    {
        // 1. Login sebagai Admin
        // Pastikan Factory kamu punya state 'admin', atau set manual seperti ini:
        $admin = User::factory()->create(['user_role' => 'admin']);

        // 2. Masuk halaman Admin
        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        // 3. Sukses (200 OK)
        $response->assertStatus(200);
    }
}