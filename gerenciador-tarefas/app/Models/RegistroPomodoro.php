<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistroPomodoro extends Model
{
    protected $fillable = [
        'user_id',
        'tarefa_id',
        'tempo_foco_minutos',
    ];

    // Relacionamento com o Usuário que fez o foco
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relacionamento com a Tarefa focada
    public function tarefa()
    {
        return $this->belongsTo(Tarefa::class);
    }
}