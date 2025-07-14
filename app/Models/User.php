<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles;
    protected $guard_name = 'user-api';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable =  ['company_id',
     'name',
     'email', 
     'password',
     'X_loc',
     'Y_loc',
     'face_image_url',
     'is_active', 
     'role', 
     'language_preference'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    /*public function projects()
    {
    return $this->hasMany(Project::class);
    }
    public function cvs()
    {
    return $this->hasMany(Cv::class);
    }
    public function profile()
    {
    return $this->hasOne(Profiles::class);
    }*/
    public function company() {
        return $this->belongsTo(Company::class);
    }

    public function attendanceRecords() {
        return $this->hasMany(AttendanceRecord::class);
    }

    public function leaveRequests() {
        return $this->hasMany(LeaveRequest::class);
    }

    public function wfhRequests() {
        return $this->hasMany(WfhRequest::class);
    }

    public function tasks() {
        return $this->hasMany(Task::class);
    }

    public function notifications() {
        return $this->hasMany(Notification::class);
    }

    public function shifts() {
        return $this->belongsToMany(Shift::class, 'user_shifts')->withTimestamps();
    }
    public function location() {
        return $this->belongsTo(Location::class);
    }
}


