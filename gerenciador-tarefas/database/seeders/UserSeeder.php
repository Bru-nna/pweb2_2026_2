<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Usuário administrador inicial
        User::firstOrCreate(
            ['email' => 'admin@exemplo.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('senha123'),
                'telefone' => '(49) 99999-0000',
                'cargo' => 'Administrador do Sistema',
                'status' => 'ativo',
            ]
        );

        // Usuários extras (para popular os selects de responsável)
        User::factory()->count(5)->create([
            'status' => 'ativo',
        ]);
    }
}