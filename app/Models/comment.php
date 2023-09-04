<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class comment extends Model
{
    use HasFactory;
    protected $fillable = [
        'body',
        'post_id'
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}