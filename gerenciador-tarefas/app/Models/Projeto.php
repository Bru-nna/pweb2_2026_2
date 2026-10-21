<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Projeto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'imagem',
        'descricao',
        'equipe_id',
        'cliente',
        'orcamento',
        'data_inicio',
        'data_fim',
        'status',
    ];

    // Equipe responsável pelo projeto
    public function equipe()
    {
        return $this->belongsTo(Equipe::class, 'equipe_id');
    }

    public function tarefas()
    {
        return $this->hasMany(Tarefa::class);
    }
}