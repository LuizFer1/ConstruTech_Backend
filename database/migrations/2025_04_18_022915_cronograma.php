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
        Schema::create('Cronograma', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('informacoes_do_projeto_id');
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->enum('Status', ['pendente','em_processo', 'concluida' , 'atrasado']);
            $table->date('data_inicio');
            $table->date('data_termino_previsto');
            $table->string('Equipe_Responsavel');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('informacoes_do_projeto_id')
            ->references('id')
            ->on('informacoes_do_projeto')
            ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('Cronograma');
    }
};
