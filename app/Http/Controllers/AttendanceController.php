<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreAttendanceRequest;
use App\Models\AttendanceRecord;
use App\Models\Geofence;
use App\Models\Company;
use App\Http\Resources\attendance;
use App\Http\Requests\createCompanyRequest;
use Illuminate\Support\Facades\Auth;
use App\Traits\Uploadfile;

class AttendanceController extends BaseController
{
    use Uploadfile;
    public function createCompany(createCompanyRequest $request)
    {
        $data = $request->validated();
        $data['subscription_plan'] = $data['subscription_plan'] ?? 'Free';
        $company = Company::create($data);
        return $this->sendResponse($company, 'Company created successfully.');
    }
    public function Attendence(StoreAttendanceRequest $request)
    {
      $user = $request->user();
      $data = $request->validated();
      $geofence = Geofence::where('company_id', $user->company_id)->first();
      if (!$geofence)
       {
         return $this->sendError('No geofence defined for the company.', [], 404);
        }
       $distance = $this->haversineDistance(
        $data['gps_lat'],
        $data['gps_lng'],
        $geofence->latitude,
        $geofence->longitude
       );
     if ($distance > $geofence->radius_in_meters)
     {
        return $this->sendError('You are outside the company geofence. Attendance denied.', [
            'distance_in_meters' => $distance
        ], 403);
     }
     $recentRecord = AttendanceRecord::where('user_id', $user->id)
        ->where('check_type', $data['check_type'])
        ->where('check_time', '>=', now()->subMinute())
        ->first();

     if ($recentRecord)
     {
        return $this->sendError('You have already submitted attendance of this type within the last minute.', [], 409);
     }
      if ($request->hasFile('photo_url'))
      {
        $path = $this->storeFile($request->file('photo_url'), 'attendances', null, 'public_uploads');
        $data['photo_url'] = asset('uploads/' . $path);
      }
      $data['user_id'] = $user->id;
      $data['check_time'] = now();
      $attendance = AttendanceRecord::create($data);
      return $this->sendResponse(new attendance($attendance), 'Attendance recorded successfully');
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
