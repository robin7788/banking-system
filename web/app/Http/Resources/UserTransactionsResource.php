<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserTransactionsResource extends JsonResource
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
            'is_credit' => $this->is_credit == 1,
            'amount' => $this->amount,
            'note' => $this->note,
            'from_currency' => $this->from_currency,
            'to_currency' => $this->to_currency,
            'charge' => $this->charge,
            'ref' => $this->ref,
            'balance' => $this->balance,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d') : '',
        ];
    }
}
