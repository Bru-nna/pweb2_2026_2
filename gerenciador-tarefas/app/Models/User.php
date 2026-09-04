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

        // Relacionamento 1:1 -> Usuário é responsável por um Projeto
    public function projetoResponsavel()
    {
        return $this->hasOne(Projeto::class, 'responsavel_id');
    }

    // Relacionamento com tarefas (se um usuário pode ter tarefas)
    public function tarefas()
    {
        return $this->hasMany(Tarefa::class);
    }
}