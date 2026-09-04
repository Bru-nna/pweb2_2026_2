<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoriaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nome' => fake()->unique()->randomElement([
                'Trabalho', 'Pessoal', 'Estudos', 'Urgente',
                'Financeiro', 'Saúde', 'Casa', 'Reuniões',
            ]),
            'descricao' => fake()->sentence(8),
            'cor' => fake()->hexColor(),
        ];
    }
}