<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class profiles extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'full_name', 'phone','avatar'];
    public function user()
     {
        return $this->belongsTo(User::class);
     }

}
