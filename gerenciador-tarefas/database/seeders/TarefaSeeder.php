<?php

namespace Database\Seeders;

use App\Models\Tarefa;
use Illuminate\Database\Seeder;

class TarefaSeeder extends Seeder
{
    public function run(): void
    {
        Tarefa::factory()->count(20)->create();
    }
}