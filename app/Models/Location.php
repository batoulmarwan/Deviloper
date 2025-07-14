<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $fillable = ['id_user','X_loc', 'Y_loc', 'XX_loc','YY_loc'];
    public function user() {
        return $this->belongsToMany(User::class);
    }
}
