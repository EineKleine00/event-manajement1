<?php

namespace Tests\Unit\Model;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function event_memiliki_kolom_yang_bisa_diisi()
    {
        $event = new Event();
        
        // Kolom yang wajib ada di $fillable
        $expected = ['name', 'description', 'date', 'created_by'];
        
        // Cek apakah kolom tersebut ada di daftar fillable model
        foreach ($expected as $column) {
            $this->assertTrue(
                in_array($column, $event->getFillable()),
                "Kolom '{$column}' belum ada di \$fillable pada Event.php"
            );
        }
    }

    /** @test */
    public function event_dimiliki_oleh_user()
    {
        // 1. Buat User
        $user = User::factory()->create();

        // 2. Buat Event milik user tersebut
        $event = Event::factory()->create(['created_by' => $user->id]);

        // 3. Cek relasi balik (Event -> User)
        // Pastikan di Event.php function relasinya bernama 'user' atau 'creator'
        // Default biasanya kita namakan: public function user()
        $this->assertInstanceOf(User::class, $event->user);
        $this->assertEquals($user->id, $event->user->id);
    }

    /** @test */
    public function event_mendukung_soft_delete()
    {
        $event = Event::factory()->create();
        
        $event->delete();

        $this->assertSoftDeleted('events', [
            'id' => $event->id
        ]);
    }
}