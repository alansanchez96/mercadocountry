<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'product',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'stock' => $this->stock,
                'measures' => $this->measures,
                'slug' => $this->slug,
            ],
            'relationships' => [
                'images' => $this->images->pluck('url'),
                'subcategories' => [
                    //'name' => $this->subcategory->name
                ],
                'brand' => [
                    //'name' => $this->brand->name
                ]
            ],
            'links' => [
                'self' => route('products.show', $this->slug)
            ]
        ];
    }
}
