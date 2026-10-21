<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Equipe;
use App\Models\User;

class EquipeSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = User::where('status', 'ativo')->get();

        if ($usuarios->count() < 3) {
            $this->command->warn('Poucos usuários ativos — rode o UserSeeder antes.');
            return;
        }

        // Cria 4 equipes
        for ($i = 0; $i < 4; $i++) {
            $lider = $usuarios->random();

            $equipe = Equipe::create([
                'nome' => 'Equipe ' . chr(65 + $i),
                'cor'  => ['#FF5733', '#33FF57', '#3357FF', '#F333FF'][$i % 4],
                'lider_id' => $lider->id,
            ]);

            // Integrantes: 2 a 4 usuários aleatórios (o líder pode ou não estar aqui)
            $integrantes = $usuarios->random(rand(2, min(4, $usuarios->count())))
                                     ->pluck('id')
                                     ->toArray();

            // Garante que o líder esteja entre os integrantes
            if (!in_array($lider->id, $integrantes)) {
                $integrantes[] = $lider->id;
            }

            $equipe->integrantes()->sync($integrantes);
        }
    }
}