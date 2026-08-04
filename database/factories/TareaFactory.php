<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Tarea>
 */
class TareaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'titulo' => fake()->sentence(4),
            'descripcion' => fake()->paragraph(),
            'estado' => fake()->randomElement(['Pendiente', 'En progreso', 'Completada']),
            'fecha_limite' => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'prioridad' => fake()->randomElement(['Baja', 'Media', 'Alta']),
        ];
    }
}