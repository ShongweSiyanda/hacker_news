<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stories extends Model
{
    protected $fillable = [
        'story_id',
        'type',
        'by',
        'time',
        'score',
        'title',
        'url',
        'comments'
    ];
    protected $casts = [
        'url' => 'array',
        'comments' => 'array'
    ];
}
