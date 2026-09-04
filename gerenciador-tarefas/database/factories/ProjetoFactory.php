<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjetoFactory extends Factory
{
    public function definition(): array
    {
        $dataInicio = fake()->dateTimeBetween('-6 months', 'now');

        return [
            'nome' => fake()->unique()->catchPhrase(),
            'descricao' => fake()->paragraph(),
            'responsaveis' => fake()->name(),
            'cliente' => fake()->company(),
            'orcamento' => fake()->randomFloat(2, 1000, 50000),
            'data_inicio' => $dataInicio,
            'data_fim' => fake()->optional(0.6)->dateTimeBetween($dataInicio, '+6 months'),
            'status' => fake()->randomElement(['planejado', 'em_andamento', 'concluido', 'cancelado']),
            'responsavel_id' => User::inRandomOrder()->value('id'),
        ];
    }
}