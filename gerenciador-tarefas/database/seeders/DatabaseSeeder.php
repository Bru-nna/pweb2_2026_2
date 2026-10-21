<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategoriaSeeder::class,
            EquipeSeeder::class,
            ProjetoSeeder::class,
            TarefaSeeder::class
               
        ]);
    }
}