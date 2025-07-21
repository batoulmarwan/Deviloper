<?php

namespace App\Http\Controllers;
use App\Http\Requests\StoreLeaveRequest;
use App\Http\Requests\StoreWfhRequest;
use App\Http\Resources\RequestResource;
use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use App\Models\WfhRequest;
use Illuminate\Support\Facades\Auth;

class RequestController extends BaseController
{
    public function storeLeaveRequest(StoreLeaveRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();  

        $leaveRequest = LeaveRequest::create($data);
        return $this->sendResponse($leaveRequest, 'Leave request submitted successfully');
    }

    public function storeWfhRequest(StoreWfhRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        $wfhRequest = WfhRequest::create($data);

        return $this->sendResponse($wfhRequest, 'WFH request submitted successfully');
    }
    public function myRequests()
   {
     $user = Auth::user();
     $leaveRequests = LeaveRequest::where('user_id', $user->id)->get();
     $wfhRequests = WfhRequest::where('user_id', $user->id)->get();
     $allRequests = $leaveRequests->concat($wfhRequests)->sortByDesc('created_at')->values();
     return $this->sendResponse(RequestResource::collection($allRequests), 'Requests fetched successfully');
    }
}
