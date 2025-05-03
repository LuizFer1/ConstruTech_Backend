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
        Schema::create('tarefas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('titulo');
            $table->string('descricao');
            $table->unsignedBigInteger('etapa_id');
            $table->unsignedBigInteger('status_id');
            $table->foreign('etapa_id')->references('id')->on('etapas');
            $table->foreign('status_id')->references('id')->on('status');
            $table->date('data_inicio');
            $table->date('data_fim')->nullable();
            $table->date('data_fim_previsto')->nullable();
            $table->unsignedBigInteger('orcamento')->nullable();
            $table->float('andamento', 2)->nullable();
            $table->string('cor', 7)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarefas');
    }
};
