<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'telefone',
        'cargo',
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relacionamento com projetos (se um usuário pode ter projetos)
    public function projetos()
    {
        return $this->hasMany(Projeto::class);
    }

    // Relacionamento com tarefas (se um usuário pode ter tarefas)
    public function tarefas()
    {
        return $this->hasMany(Tarefa::class);
    }
}