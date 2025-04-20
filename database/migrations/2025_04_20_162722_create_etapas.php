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
        Schema::create('Etapas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('Cronograma_id');
            $table->unsignedBigInteger('Usuario_id');
            $table->text('descricao')->nullable();
            $table->enum('Status', ['pendente','em_processo', 'concluida' , 'atrasado']);
            $table->date('data_Atualizacao');
            $table->string('Responsavel_Atualizacao');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('Cronograma_id')
            ->references('id')
            ->on('Cronograma')
            ->onDelete('cascade');

            $table->foreign('Usuario_id')
            ->references('id')
            ->on('Usuario')
            ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('Etapas');
    }
};
