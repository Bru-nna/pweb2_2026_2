<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Projeto;
use App\Models\Equipe;
use App\Models\User;

class ProjetoSeeder extends Seeder
{
    public function run(): void
    {
        // Pega as equipes e usuários que já foram criados
        $equipes = Equipe::all();
        $usuarios = User::all();

        if ($equipes->isEmpty()) {
            return; // Evita erro se não houver equipes
        }

        // Cria 5 projetos de teste
        for ($i = 0; $i < 5; $i++) {
            Projeto::create([
                'nome' => 'Projeto de Teste ' . ($i + 1),
                'descricao' => 'Descrição do projeto ' . ($i + 1),
                'equipe_id' => $equipes->random()->id,
                'cliente' => 'Cliente ' . chr(65 + $i),
                'orcamento' => rand(5000, 50000),
                'data_inicio' => now()->format('Y-m-d'),
                'data_fim' => now()->addMonths(2)->format('Y-m-d'),
                'status' => 'planejado',
            ]);
        }
    }
}