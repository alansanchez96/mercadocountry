<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement([
                'Computacion',
                'Celulares',
                'Consolas y Videojuegos',
                'Indumentarias',
                'Electrodomesticos',
                'Libros',
                'Vehiculos',
                'Alimentos',
                'Muebles',
                'Limpieza',
                'Herramientas',
                'Deportes',
                'Entrenamiento',
                'Entretenimiento'
            ])
        ];
    }
}
