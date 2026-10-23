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

    // Minutos de foco (Pomodoro) neste projeto, por usuário: [user_id => minutos]
    public function minutosPorUsuario()
    {
        return RegistroPomodoro::join('tarefas', 'tarefas.id', '=', 'registro_pomodoros.tarefa_id')
            ->where('tarefas.projeto_id', $this->id)
            ->selectRaw('registro_pomodoros.user_id, SUM(registro_pomodoros.tempo_foco_minutos) as total')
            ->groupBy('registro_pomodoros.user_id')
            ->pluck('total', 'user_id');
    }
}