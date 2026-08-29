<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projeto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'data_inicio',
        'data_fim',
        'status'
    ];

    public function tarefas()
    {
        return $this->hasMany(Tarefa::class);
    }
}