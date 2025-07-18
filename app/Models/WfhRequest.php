<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WfhRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'reason', 'status','date','pool_url'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
