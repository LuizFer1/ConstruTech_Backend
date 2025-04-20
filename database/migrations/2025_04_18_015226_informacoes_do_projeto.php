<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('informacoesDoProjeto', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome_projeto');
            $table->text('descricao')->nullable();
            $table->enum('Responsavel', ['planejada', 'em_andamento', 'concluida'])->default('planejada');
            $table->date('data_inicio');
            $table->date('data_termino_previsto');
            $table->timestamps('Ultima_Atualizacao');
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('informacoesDoProjeto');
    }
};
