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
        Schema::create('obra', function (Blueprint $table) {
            $table->id('id');
            $table->string('nome_obra');
            $table->text('descricao')->nullable();
            $table->enum('status', ['planejada', 'em_andamento', 'concluida'])->default('planejada');
            $table->date('data_inicio');
            $table->date('data_termino_previsto');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('obra');
    }
};
