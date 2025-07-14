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
            'message' => 'You have already submitted this attendance recently.',
        ], 429); 
    }
        $geofence = Geofence::where('company_id', $user->company_id)->first();
        $outsideGeofence = true;
        if ($request->gps_lat && $request->gps_lng && $geofence) {
            $outsideGeofence = !$this->isWithinGeofence(
                $request->gps_lat,
                $request->gps_lng,
                $geofence->latitude,
                $geofence->longitude,
                $geofence->radius_in_meters
            );
        }
        $attendance = AttendanceRecord::create([
            'user_id' => $user->id,
            'check_type' => $request->check_type,
            'check_time' => now(),
            'gps_lat' => $request->gps_lat,
            'gps_lng' => $request->gps_lng,
            'photo_url' => $request->photo_url,
            'is_fake_gps' => false,
            'outside_geofence' => $outsideGeofence,
            'synced' => false,
        ]);
        return $this->sendResponse(new attendance($attendance), "Successfully retrieved all Project.");
    }
    
    private function isWithinGeofence($userLat, $userLng, $fenceLat, $fenceLng, $radiusInMeters)
{
    $earthRadius = 6371000; 

    $dLat = deg2rad($fenceLat - $userLat);
    $dLng = deg2rad($fenceLng - $userLng);

    $a = sin($dLat/2) * sin($dLat/2) +
         cos(deg2rad($userLat)) * cos(deg2rad($fenceLat)) *
         sin($dLng/2) * sin($dLng/2);

    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    $distance = $earthRadius * $c;

    return $distance <= $radiusInMeters;
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
