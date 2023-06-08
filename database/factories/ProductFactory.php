<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()-> word();
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()-> sentence(),
            'price' => fake()-> randomFloat(2, 100, 99999),//(decimal,lower,upper)
            'stock' => fake()-> randomDigit(),
            'measures' => json_encode([
                'altura' => fake()-> randomDigit(2),
                'ancho' => fake()-> randomDigit(2),
                'largo' => fake()-> randomDigit(2),
            ]),
            'status' => fake()-> randomElement([
                Product::NEW,
                Product::USED,
            ]),
            'subcategory_id'=> Subcategory::all()->random()->id,
            'brand_id' => Brand::all()->random()->id

            // 'stock' => $this->faker->randomDigit()

        ];
    }
}
