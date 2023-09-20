<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
        protected $fillable = [
        'body',
        'station_id',
        'user_id'
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'DESC');
    }
    public function station(){
        return $this->belongsTo(Station::class);
    }
}