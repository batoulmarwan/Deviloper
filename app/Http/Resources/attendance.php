<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class attendance extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'check_time' => $this->check_time,
            'check_type' => $this->check_type,
            'gps_lat' => $this->gps_lat,
            'gps_lng' => $this->gps_lng,
            'photo_url' => $this->photo_url,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
