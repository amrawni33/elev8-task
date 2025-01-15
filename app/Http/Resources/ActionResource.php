<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type'=> $this->type,
            'result'=> $this->type,
            'customer' => new UserResource($this->whenLoaded('customer')),
            'employee'=>new UserResource($this->whenLoaded('user')),
        ];
    }
}
