<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Geofence extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id', 'name', 'latitude',
        'longitude', 'radius_in_meters'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
