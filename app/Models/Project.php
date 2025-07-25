<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'user_id','image_path'];

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
