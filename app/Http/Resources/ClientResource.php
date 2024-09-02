<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
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
            'surnom' => $this->surnom,
            'adresse' => $this->adresse,
            'telephone' => $this->telephone,
            'user' => new UserResource($this->whenLoaded('user')), 
        ];
    }

    
}
