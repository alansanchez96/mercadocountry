<?php

namespace App\Http\Resources\Products;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'attributes' => [
                'id' => $this->id,
                'name' => $this->name,
                'price' => $this->price,
                'brand' => $this->brand->name,
                'images' => $this->images->pluck('url'),
            ],
            'links' => [
                'self' => route('products.show', $this->id)
            ]
        ];
    }
}
