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
        Schema::create('etapas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('nome');
            $table->unsignedBigInteger('responsavel_id');
            $table->unsignedBigInteger('obra_id');
            $table->unsignedBigInteger('status_id');
            $table->foreign('responsavel_id')->references('id')->on('colaboradores');
            $table->foreign('obra_id')->references('id')->on('obras');
            $table->foreign('status_id')->references('id')->on('status');
            $table->date('data_inicio');
            $table->date('data_fim')->nullable();
            $table->date('data_fim_previsto')->nullable();
            $table->float('andamento', 2)->nullable();
            $table->unsignedBigInteger('orcamento')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etapas');
    }
};
