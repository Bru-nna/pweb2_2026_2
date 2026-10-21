<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projetos', function (Blueprint $table) {
            $table->foreignId('equipe_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('equipes')
                  ->nullOnDelete();
        });

        Schema::table('projetos', function (Blueprint $table) {
            // remove a FK antiga, se existir
            $table->dropForeign(['responsavel_id']);
            $table->dropColumn('responsavel_id');
        });
    }

    public function down(): void
    {
        Schema::table('projetos', function (Blueprint $table) {
            $table->foreignId('responsavel_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('users')
                  ->nullOnDelete();
        });

        Schema::table('projetos', function (Blueprint $table) {
            $table->dropForeign(['equipe_id']);
            $table->dropColumn('equipe_id');
        });
    }
};