<?php

namespace Tests\Unit\Model;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_memiliki_role_default_user()
    {
        // 1. Buat user tanpa menentukan role
        $user = User::factory()->create();

        // 2. Cek apakah otomatis jadi 'user'
        $this->assertEquals('user', $user->user_role);
    }

    /** @test */
    public function user_bisa_memiliki_banyak_event()
    {
        $user = User::factory()->create();

        // Buat event yang dikaitkan ke user ini lewat 'created_by'
        $event1 = Event::factory()->create(['created_by' => $user->id]);
        $event2 = Event::factory()->create(['created_by' => $user->id]);

        // Cek relasi
        $this->assertTrue($user->events->contains($event1));
        $this->assertTrue($user->events->contains($event2));
        $this->assertCount(2, $user->events);
    }

    /** @test */
    public function user_mendukung_soft_delete()
    {
        $user = User::factory()->create();
        
        // Hapus user
        $user->delete();

        // Cek apakah masih ada di database tapi ada tanda deleted_at
        $this->assertSoftDeleted('users', [
            'id' => $user->id
        ]);
    }
}