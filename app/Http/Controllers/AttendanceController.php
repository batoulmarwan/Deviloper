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
    public function Attendence(Request $request)
    {
        $user = Auth::user();

        $geofence = Geofence::where('company_id', $user->company_id)->first();
    
        if (!$geofence) {
            return response()->json(['message' => 'لا يوجد نطاق جغرافي محدد للشركة'], 404);
        }
    
        $distance = $this->haversineDistance(
            $request->gps_lat,
            $request->gps_lng,
            $geofence->latitude,
            $geofence->longitude
        );
    

        if ($distance > $geofence->radius_in_meters) {
            return response()->json([
                'message' => 'لا يمكنك تسجيل الحضور، أنت خارج النطاق الجغرافي للشركة.',
                'distance_in_meters' => $distance
            ], 403);
        }
        $recentRecord = AttendanceRecord::where('user_id', $user->id)
        ->where('check_type', $request->check_type)
        ->where('check_time', '>=', now()->subMinute())
        ->first();

        if ($recentRecord) {
           return response()->json([
            'message' => 'لقد قمت بتسجيل حضورك لهذا النوع خلال الدقيقة الماضية، حاول لاحقًا.'
           ], 409);
        }
        $record = AttendanceRecord::create([
            'user_id'    => $user->id,
            'check_time' => now(),
            'gps_lat'    => $request->gps_lat,
            'gps_lng'    => $request->gps_lng,
            'photo_url'  => $request->photo_url ?? null,
            'check_type' => $request->check_type,
        ]);
    
        return response()->json([
            'message' => 'تم تسجيل الحضور بنجاح',
            'distance_in_meters' => $distance,
            'record' => $record
        ]);
    }
     private function haversineDistance($lat1, $lon1, $lat2, $lon2)
            {
                 $earthRadius = 6371000; 
                 $latFrom = deg2rad($lat1);
                 $lonFrom = deg2rad($lon1);
                 $latTo   = deg2rad($lat2);
                 $lonTo   = deg2rad($lon2);

                 $latDelta = $latTo - $latFrom;
                 $lonDelta = $lonTo - $lonFrom;

                 $a = sin($latDelta / 2) ** 2 +
                      cos($latFrom) * cos($latTo) *
                       sin($lonDelta / 2) ** 2;

                     $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
                  return $earthRadius * $c;
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
