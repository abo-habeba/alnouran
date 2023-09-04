<?php

namespace App\Models;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class,'user_stations');
    }
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
