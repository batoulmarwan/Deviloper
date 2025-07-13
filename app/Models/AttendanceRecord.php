<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'check_time', 'gps_lat', 'gps_lng',
        'photo_url', 'is_fake_gps', 'is_outside_geofence',
        'synced', 'check_type'
    ];

    protected $casts = [
        'is_fake_gps' => 'boolean',
        'is_outside_geofence' => 'boolean',
        'synced' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
