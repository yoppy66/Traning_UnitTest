<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';

    // コレやっとかないと勝手にintに変換されちゃう
    protected $casts = [
        'status' => 'boolean',
    ];

    protected $fillable = [
        'name',
    ];
}
