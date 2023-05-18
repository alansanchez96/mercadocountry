<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dispatchData = [
            'state' => fake()->state,
            'city' => fake()->city,
            'address' => fake()->address,
        ];

        $detailsProducts = [
            'name' => fake()->word(),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 100, 100000), //(decimal,lower,upper)
        ];

        $shippingCost = fake()->randomFloat(2, 100, 400);

        return [
            'contact' => fake()->sentence(),
            'phone' => fake()->e164PhoneNumber(),
            'status' => fake()->randomElement([
                'PENDIENTE',
                'RECIBIDO',
                'ENVIADO',
                'ENTREGADO',
                'CANCELADO'
            ]),
            'dispatch_type' => fake()->randomElement([
                'DOMICILIO',
                'RETIRO DEPOSITO',
                'DEPOSITO SUCURSAL'
            ]),
            'dispatch_address' => json_encode($dispatchData),
            'details_product' => json_encode($detailsProducts),
            'shipping_cost' => $shippingCost,
            'total' => $detailsProducts['price'] + $shippingCost,
            'user_id' => User::all()->random()->id
        ];
    }
}
