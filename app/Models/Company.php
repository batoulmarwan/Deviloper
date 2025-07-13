<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'subscription_plan'];

    public function users() {
        return $this->hasMany(User::class);
    }

    public function shifts() {
        return $this->hasMany(Shift::class);
    }

    public function geofences() {
        return $this->hasMany(Geofence::class);
    }
}


