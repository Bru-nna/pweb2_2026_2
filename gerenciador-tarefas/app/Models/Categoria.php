<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'cor'
    ];

    public function tarefas()
    {
        return $this->hasMany(Tarefa::class);
    }
}