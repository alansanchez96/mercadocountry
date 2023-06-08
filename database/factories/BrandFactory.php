<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'X-BOX',
            'Apple',
            'BGH',
            'Philips',
            'Philco',
            'Lenovo',
            'Sony',
            'PlayStation',
            'HP',
            'Atma',
            'Nintendo',
            'Nokia',
            'Motorola',
            'Xiaomi',
            'LG',
            'Whirpool'
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name)
        ];
    }
}
