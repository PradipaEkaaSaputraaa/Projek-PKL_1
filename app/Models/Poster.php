<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poster extends Model
{
    use HasFactory;

    protected $fillable = ['images'];

    // Casts memastikan data JSON diubah menjadi array PHP
    protected $casts = [
        'images' => 'array',
    ];
}