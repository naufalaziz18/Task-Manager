<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',       // Kolom nama pengguna
        'title',      // Kolom judul tugas
        'user_id',    // ID pengguna
        'is_completed', // Status selesai
    ];
}
