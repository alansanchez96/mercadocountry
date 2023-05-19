<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::factory(50)
            ->create()
            ->each(
                fn ($product) =>
                Image::factory(3)
                    ->create([
                        'url' => 'tests/products/' . fake()->image('public/storage/tests/products/', 640, 480, null, false),
                        'imageable_id' => $product->id,
                        'imageable_type' => Product::class
                    ])
            );
    }
}
