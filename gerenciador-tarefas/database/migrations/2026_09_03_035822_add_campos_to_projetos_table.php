<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projetos', function (Blueprint $table) {
            // Responsáveis (pode ser um texto com nomes separados por vírgula)
            $table->string('responsaveis')->nullable()->after('descricao');
            
            // Cliente
            $table->string('cliente')->nullable()->after('responsaveis');
            
            // Orçamento (valor em reais)
            $table->decimal('orcamento', 10, 2)->nullable()->after('cliente');
        });
    }

    public function down(): void
    {
        Schema::table('projetos', function (Blueprint $table) {
            $table->dropColumn(['responsaveis', 'cliente', 'orcamento']);
        });
    }
};