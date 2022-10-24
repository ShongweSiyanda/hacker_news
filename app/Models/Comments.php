<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    protected $fillable = [
        'comment_id',
        'by',
        'text',
        'time',
        'type',
        'story_id',
    ];
    protected $casts = [
        'text' => 'array',
    ];
}
