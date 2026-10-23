<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Tarefa;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services. comentario teste
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Tarefas disponíveis no seletor do modal do Pomodoro (presente em todas as páginas)
        View::composer('layouts.app', function ($view) {
            if (auth()->check()) {
                $view->with('tarefasPomodoro', Tarefa::with('projeto')->orderBy('titulo')->get());
            }
        });
    }
}
