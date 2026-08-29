<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarefa extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descricao',
        'prioridade',
        'data_vencimento',
        'status',
        'projeto_id',
        'categoria_id'
    ];

    public function projeto()
    {
        return $this->belongsTo(Projeto::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}