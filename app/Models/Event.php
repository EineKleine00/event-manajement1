<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name', 
        'description', 
        'date',   
        'created_by'  
    ];

    /**
     * Relasi ke Pembuat Event
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    /**
     * Relasi ke Pembuat Event (Nama Asli)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi ke Anggota (Petugas/Sponsor) via tabel pivot 'event_user'
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'event_user', 'event_id', 'user_id')
                    ->withPivot('role')
                    ->withTimestamps();
        
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    /**
     * Relasi ke Task
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}