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
        Schema::create('midia_andamento_obra', function (Blueprint $table) {
            $table->id(); 
            $table->date('data_do_registro'); 
            $table->unsignedBigInteger('id_obra');
            $table->unsignedBigInteger('id_responsavel'); // ID do colaborador
            $table->string('tempo_climatico')->nullable();
            $table->string('tempo_climatico_t_max')->nullable();
            $table->string('tempo_climatico_t_min')->nullable();
            $table->string('tempo_climatico_observacao')->nullable();
            $table->string('servico_executado');
            $table->string('etapa_frente');
            $table->string('atrasos');
            $table->string('visitas_tecnicas');
            $table->string('acidente');
            $table->string('problemas_operacionais');
            $table->text('descricao')->nullable();
            $table->foreign('id_obra')->references('id')->on('obras'); 
            $table->foreign('id_responsavel')->references('id')->on('colaboradores');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('midia_andamento_obra');
    }
};