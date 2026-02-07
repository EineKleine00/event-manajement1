<?php

namespace Tests\Feature\User;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserEventCRUDTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_bisa_melihat_daftar_event()
    {
        // 1. Login user
        $user = User::factory()->create();
        $this->actingAs($user);

        // 2. Buat 1 event dummy di database
        Event::factory()->create([
            'name' => 'Konser Musik Keren',
            'created_by' => $user->id
        ]);

        // 3. Buka halaman daftar event
        // Pastikan nanti route-nya bernama 'events.index'
        $response = $this->get(route('events.index'));

        // 4. Pastikan sukses & ada tulisan judul eventnya
        $response->assertStatus(200);
        $response->assertSee('Konser Musik Keren');
    }

    /** @test */
    public function user_bisa_membuat_event_baru()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Data event yang mau diinput
        $eventData = [
            'name' => 'Seminar Teknologi AI',
            'description' => 'Belajar AI bareng Gemini',
            'date' => '2026-12-31',
        ];

        // User submit data ke route 'events.store'
        $response = $this->post(route('events.store'), $eventData);

        // Cek Database: Apakah data masuk?
        $this->assertDatabaseHas('events', [
            'name' => 'Seminar Teknologi AI',
            'created_by' => $user->id, // Pastikan yang buat adalah user ini
        ]);

        // Setelah simpan, biasanya redirect ke halaman index
        $response->assertRedirect(route('events.index'));
    }

    /** @test */
    public function user_bisa_mengupdate_event_miliknya()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // User punya event lama
        $event = Event::factory()->create(['created_by' => $user->id]);

        // User mau ganti nama eventnya
        $updateData = [
            'name' => 'Nama Event Baru Nih',
            'description' => 'Deskripsi baru',
            'date' => '2027-01-01',
            'location' => 'Jakarta Convention Center',
        ];

        // Kirim request PUT ke route 'events.update'
        $response = $this->put(route('events.update', $event->id), $updateData);

        // Cek Database: Harusnya namanya berubah
        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'name' => 'Nama Event Baru Nih',
        ]);

        $response->assertRedirect(route('events.index'));
    }

    /** @test */
    public function user_bisa_menghapus_event_miliknya()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // User punya event
        $event = Event::factory()->create(['created_by' => $user->id]);

        // Hapus event
        $response = $this->delete(route('events.destroy', $event->id));

        // Cek Database: Harusnya sudah terhapus (Soft Delete)
        $this->assertSoftDeleted('events', ['id' => $event->id]);

        $response->assertRedirect(route('events.index'));
    }
}