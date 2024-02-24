<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentsResource extends JsonResource
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
            'status' => $this->status,
            'transaction_amount' => $this->transaction_amount,
            'installments' => $this->installments,
            'token' => $this->token,
            'payment_method_id' => $this->payment_method_id,
            'payer' => [
                'entity_type' => $this->payer->entity_type,
                'type' => $this->payer->type,
                'email' => $this->payer->email,
                'identification' => [
                    'type' => $this->payer->identification_type,
                    'number' => $this->payer->identification_number,
                ],
            ],
            'notification_url' => $this->notification_url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
