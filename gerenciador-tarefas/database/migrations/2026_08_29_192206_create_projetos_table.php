<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projetos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->date('data_inicio');
            $table->date('data_fim')->nullable();
            $table->enum('status', ['planejado', 'em_andamento', 'concluido', 'cancelado'])
                  ->default('planejado');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projetos');
    }
};