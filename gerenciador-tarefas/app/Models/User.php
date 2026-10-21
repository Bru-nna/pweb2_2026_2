<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'imagem',
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

    // Relacionamento com tarefas
    public function tarefas()
    {
        return $this->hasMany(Tarefa::class);
    }

    // Equipes em que o usuário é líder
    public function equipesLideradas()
    {
        return $this->hasMany(Equipe::class, 'lider_id');
    }

    // Equipes em que o usuário é integrante (N:N)
    public function equipes()
    {
        return $this->belongsToMany(Equipe::class, 'equipe_user')
                    ->withTimestamps();
    }
}