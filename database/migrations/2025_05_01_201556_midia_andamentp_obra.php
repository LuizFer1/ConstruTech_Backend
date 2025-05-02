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
            $table->id('id_midia_andamento_obra'); 
            $table->enum('tipo_de_midia', ['Video', 'Foto']);
            $table->string('arquivo_da_midia');
            $table->date('data_do_registro'); 
            $table->text('descricao')->nullable();
            $table->string('local_da_obra'); 
            $table->string('responsavel_pelo_envio'); 
            $table->unsignedBigInteger('id_obra');
            $table->foreign('id_obra')->references('id')->on('obras')->onDelete('cascade'); 
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