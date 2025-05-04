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
        Schema::create('colaborador_tarefa', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('colaborador_id');
            $table->unsignedBigInteger('tarefa_id');
            $table->foreign('colaborador_id')->references('id')->on('colaboradores');
            $table->foreign('tarefa_id')->references('id')->on('tarefas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colaborador_tarefa');
    }
};
