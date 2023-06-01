<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'cart',
            'id' => $this->id,
            'attributes' => [
                'quantity' => $this->quantity,
                'total_price_product' => $this->quantity * $this->products->price
            ],
            'relationships' => [
                'products' => ProductResource::make($this->products)  
            ],
            'links' => [
                // 'self' => route('products.show', $this->id)
            ]
        ];
    }
}
