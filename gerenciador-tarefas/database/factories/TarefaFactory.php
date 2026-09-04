<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\Projeto;
use Illuminate\Database\Eloquent\Factories\Factory;

class TarefaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'titulo' => fake()->sentence(4),
            'descricao' => fake()->paragraph(),
            'prioridade' => fake()->randomElement(['baixa', 'media', 'alta', 'urgente']),
            'data_vencimento' => fake()->optional(0.8)->dateTimeBetween('now', '+3 months'),
            'status' => fake()->randomElement(['pendente', 'em_andamento', 'concluida', 'cancelada']),
            'projeto_id' => Projeto::inRandomOrder()->value('id'),
            'categoria_id' => Categoria::inRandomOrder()->value('id'),
        ];
    }
}