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
        Schema::create('obras', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->unsignedBigInteger('construtor_id');
            $table->unsignedBigInteger('responsavel_id');
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('status_id');
            $table->foreign('construtor_id')->references('id')->on('users');
            $table->foreign('responsavel_id')->references('id')->on('colaboradores');
            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->foreign('status_id')->references('id')->on('status');
            $table->float('andamento', 2)->nullable();
            $table->date('data_inicio')->nullable();
            $table->date('data_fim_previsto')->nullable();
            $table->date('data_fim')->nullable();
            $table->date('data_arquivamento')->nullable();
            $table->unsignedBigInteger('orcamento')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('obras', function (Blueprint $table) {
            $table->dropForeign(['responsavel_id']);
            $table->dropForeign(['cliente_id']);
            $table->dropForeign(['status_id']);
            $table->dropForeign(['construtor_id']);
        });
        Schema::dropIfExists('obras');
    }
};
