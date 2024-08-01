<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'account_number' => $this->account->account_number ?? "",
            'type' => $this->account->type ?? "",
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'dob' => $this->dob,
            'address' => $this->full_address,
            'post_code' => $this->post_code,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d') : '',
        ];
    }
}
