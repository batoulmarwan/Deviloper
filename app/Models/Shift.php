<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;
    protected $fillable = ['company_id', 'start_time', 'end_time', 'name'];

    public function company() {
        return $this->belongsTo(Company::class);
    }

    public function users() {
        return $this->belongsToMany(User::class, 'user_shifts')->withTimestamps();
    }
}
