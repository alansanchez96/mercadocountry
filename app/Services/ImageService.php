<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Image;

class ImageService
{
    /**
     * Guarda la imagen en el almacenamiento
     *
     * @param mixed $file
     */
    public function create(mixed $file, Product $product)
    {
        $path = 'img/products';

        if (!Storage::exists($path)) {
            Storage::disk('public')->makeDirectory($path, 0755, true);
        }

        $nameImage = Str::random(15);

        $image = $file->storeAs($path, $nameImage . '.png', 'public');

        $product->images()->create([
            'url' => $image,
        ]);
    }

    /**
     * Elimina la imagen del almacenamiento
     *
     * @param Model $model
     */
    public function delete(Product $product)
    {    
        $imageUrls = $product->images->pluck('url')->toArray();

        foreach ($imageUrls as $url) {
            Storage::disk('public')->delete('/img/products/' . $url);
        }

        $product->images()->delete();
    }
}
