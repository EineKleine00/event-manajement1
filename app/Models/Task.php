<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    // Sesuai tabel tasks di DB kamu (pakai is_done)
    protected $fillable = ['event_id', 'user_id', 'title', 'description', 'is_done'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class); // Pemilik task (petugas)
    }
}