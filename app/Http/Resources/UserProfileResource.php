<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'balance' => $this->balance,
            'role' => new RoleResource($this->whenLoaded('roles')),
            'total_deposit' => $this->total_deposit,
            'total_withdrawal' => $this->total_withdrawal,
        ];
    }
}
