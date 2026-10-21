<?php

namespace Database\Factories;

use App\Models\Projeto;
use App\Models\Equipe;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjetoFactory extends Factory
{
    protected $model = Projeto::class;

    public function definition(): array
    {
        return [
            'nome' => fake()->sentence(3),
            'descricao' => fake()->paragraph(),
            'equipe_id' => Equipe::factory(),
            'cliente' => fake()->company(),
            'orcamento' => fake()->randomFloat(2, 1000, 50000),
            'data_inicio' => fake()->date(),
            'data_fim' => fake()->date(),
            'status' => fake()->randomElement(['planejado', 'em_andamento', 'concluido', 'cancelado']),
        ];
    }
}