<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreAttendanceRequest;
use App\Models\AttendanceRecord;
use App\Models\Geofence;
use App\Http\Resources\attendance;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends BaseController
{
    public function Attendence(StoreAttendanceRequest $request)
    {
        $user = Auth::user();
        $recentRecord = AttendanceRecord::where('user_id', $user->id)
        ->where('check_type', $request->check_type)
        ->where('check_time', '>=', now()->subMinute())
        ->first();

    if ($recentRecord) {
        return response()->json([
            'message' => 'تم تسجيل الحضور مسبقاً خلال الدقيقة الماضية.',
        ], 429);
    }

    if ($user->X_loc !== $request->gps_lat || $user->Y_loc !== $request->gps_lng) {
        return response()->json([
            'message' => 'موقعك الحالي لا يطابق الموقع المسجل.',
            'expected' => [
                'X_loc' => $user->X_loc,
                'Y_loc' => $user->Y_loc,
            ],
            'received' => [
                'X_loc' => $request->gps_lat,
                'Y_loc' => $request->gps_lng,
            ]
        ], 403);
    }
    $attendance = AttendanceRecord::create([
        'user_id' => $user->id,
        'check_type' => $request->check_type,
        'check_time' => now(),
        'gps_lat' => $request->gps_lat,
        'gps_lng' => $request->gps_lng,
        'photo_url' => $request->photo_url,
        'is_fake_gps' => false,
        'outside_geofence' => false,
        'synced' => false,
    ]);

    return $this->sendResponse(new attendance($attendance), "تم تسجيل الحضور بنجاح.");
    }
    
    
public function history()
{
    $user = Auth::user();
    $records = AttendanceRecord::where('user_id', $user->id)
        ->orderBy('check_time', 'desc')
        ->get();

    return response()->json($records);
}

}
