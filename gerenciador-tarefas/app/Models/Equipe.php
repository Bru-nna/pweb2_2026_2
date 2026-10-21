<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Equipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'cor',
        'lider_id',
    ];

    // Líder da equipe (um User)
    public function lider()
    {
        return $this->belongsTo(User::class, 'lider_id');
    }

    // Integrantes (N:N com users)
    public function integrantes()
    {
        return $this->belongsToMany(User::class, 'equipe_user')
                    ->withTimestamps();
    }

    // Projetos sob responsabilidade da equipe
    public function projetos()
    {
        return $this->hasMany(Projeto::class, 'equipe_id');
    }
}