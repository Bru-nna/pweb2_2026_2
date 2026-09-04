<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Usuário administrador inicial para acessar o sistema.
        // Altere o e-mail/senha depois do primeiro login.
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@exemplo.com'],
            [
                'name' => 'Administrador',
                'password' => \Illuminate\Support\Facades\Hash::make('senha123'),
                'status' => 'ativo',
            ]
        );
    }
}
