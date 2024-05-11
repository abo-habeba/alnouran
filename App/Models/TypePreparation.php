<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypePreparation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'station_id',
        'user_id'
    ];
    public function latestPreparationActual()
    {
        return $this->hasOne(Preparation::class, 'typePreparation_id')->latest('actual_time');
    }
    public function preparations()
    {
        return $this->hasMany(Preparation::class);
    }
    public function user()
    {
        return $this->belongsTo(user::class);
    }
    public function station()
    {
        return $this->belongsTo(Station::class);
    }
    
   
}

