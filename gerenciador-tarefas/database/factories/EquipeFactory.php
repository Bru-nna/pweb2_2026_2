<?php

namespace Database\Factories;

use App\Models\Equipe;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Equipe>
 */
class EquipeFactory extends Factory
{
    public function definition(): array
    {
        $cores = ['#FF5733', '#33FF57', '#3357FF', '#F333FF', '#FFD733', '#33FFF5'];

        return [
            'nome' => 'Equipe ' . fake()->unique()->words(2, true),
            'cor' => fake()->randomElement($cores),
            'lider_id' => User::factory(),
        ];
    }
}