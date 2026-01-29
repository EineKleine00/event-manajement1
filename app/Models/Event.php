<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    // Sesuai tabel events di DB kamu
    protected $fillable = ['name', 'description', 'date', 'created_by'];

    // Relasi ke User (Anggota: Petugas/Sponsor) via tabel pivot 'event_user'
    public function users()
    {
        return $this->belongsToMany(User::class, 'event_user', 'event_id', 'user_id')
                    ->withPivot('role') // Agar bisa baca dia 'petugas' atau 'sponsor'
                    ->withTimestamps();
    }

    // Relasi ke Task (1 Event punya Banyak Task)
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    // Relasi ke Pembuat Event (Ketua)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}