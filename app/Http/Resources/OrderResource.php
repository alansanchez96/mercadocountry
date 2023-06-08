<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'order',
            'id' => $this->id,
            'attributes' => [
                'status' => $this->status,
                'dispatch_address' => $this->dispatch_address,
                'dispatch_type' => $this->dispatch_type,
                'details_products' => $this->details_products,
                'shipping_cost' => $this->shipping_cost,
                'total' => $this->total,
            ]
        ];
    }
}
