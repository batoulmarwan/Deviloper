<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileingResourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'full_name'       => $this->full_name,
            'phone' => $this->phone,
            'avatar' => $this->avatar ? asset( $this->avatar) : null,
        ];
    }
}
