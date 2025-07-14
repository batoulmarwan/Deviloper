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
            'check_type'=> $this->check_type,
            'check_time'=> $this->check_time,
            'gps_lat'=> $this->gps_lat,
            'gps_lng'=> $this->gps_lng,
            'photo_url'=> $this->photo_url,
            'is_fake_gps'=> $this->is_fake_gps,
            'synced'=> $this->synced,
            
        ];
    }
}
