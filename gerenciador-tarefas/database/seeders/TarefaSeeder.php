<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tarefa;
use App\Models\Projeto;
use App\Models\Categoria;

class TarefaSeeder extends Seeder
{
    public function run(): void
    {
        // Se não houver projetos ou categorias no banco, aborta a criação
        // para não dar erro de "projeto_id cannot be null"
        if (Projeto::count() === 0 || Categoria::count() === 0) {
            $this->command->warn('Projetos ou Categorias não encontrados. Tarefas não foram criadas.');
            return;
        }

        // Cria 15 tarefas usando o seu Factory
        Tarefa::factory()->count(15)->create();
    }
}