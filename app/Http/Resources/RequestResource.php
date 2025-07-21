<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        if ($this->resource instanceof \App\Models\LeaveRequest) {
            return [
                'id' => $this->id,
                'type' => 'leave',
                'from_date' => $this->from_date,
                'to_date' => $this->to_date,
                'reason' => $this->reason,
                'status' => $this->status,
                'user' => [
                    'id' => $this->user->id ?? null,
                    'name' => $this->user->name ?? null,
                ],
            ];
        } elseif ($this->resource instanceof \App\Models\WfhRequest) {
            return [
                'id' => $this->id,
                'type' => 'wfh',
                'date' => $this->date,
                'reason' => $this->reason,
                'status' => $this->status,
                'pool_url' => $this->pool_url,
                'user' => [
                    'id' => $this->user->id ?? null,
                    'name' => $this->user->name ?? null,
                ],
            ];
        }
    }
}
