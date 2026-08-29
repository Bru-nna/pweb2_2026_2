<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tarefas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->enum('prioridade', ['baixa', 'media', 'alta', 'urgente'])
                  ->default('media');
            $table->date('data_vencimento')->nullable();
            $table->enum('status', ['pendente', 'em_andamento', 'concluida', 'cancelada'])
                  ->default('pendente');
            $table->foreignId('projeto_id')
                  ->constrained('projetos')
                  ->onDelete('cascade');
            $table->foreignId('categoria_id')
                  ->constrained('categorias')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tarefas');
    }
};