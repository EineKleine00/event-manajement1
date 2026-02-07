<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function halaman_login_bisa_dibuka()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    /** @test */
    public function user_bisa_login_dengan_password_benar()
    {
        // 1. Buat User (Password otomatis di-hash oleh Model User)
        // Kita set password manual 'password' biar pasti sama.
        $user = User::factory()->create([
            'password' => 'password', 
        ]);

        // 2. Test Login via Volt Component
        Volt::test('pages.auth.login')
            ->set('form.email', $user->email)
            ->set('form.password', 'password')
            ->call('login')
            ->assertHasNoErrors()
            ->assertRedirect(route('dashboard'));
            
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function user_gagal_login_jika_password_salah()
    {
        $user = User::factory()->create([
            'password' => 'password',
        ]);

        // Login pakai password ngawur via Volt
        Volt::test('pages.auth.login')
            ->set('form.email', $user->email)
            ->set('form.password', 'salah-banget')
            ->call('login')
            ->assertHasErrors();

        // Pastikan masih tamu (Guest)
        $this->assertGuest();
    }
}