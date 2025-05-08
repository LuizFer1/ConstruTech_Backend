<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('colaboradores', function (Blueprint $table) {
        $table->id();
        $table->string('nome_completo');
        $table->unsignedBigInteger('user_id');
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->string('apelido')->nullable();
        $table->string('cpf', 14)->unique();
        $table->string('cargo');
        $table->string('setor');
        $table->string('vinculo'); // Pode ser enum futuramente
        $table->string('matricula')->unique();
        $table->date('data_admissao');
        $table->string('email')->unique();
        $table->string('telefone')->nullable();
        $table->string('cep', 9);
        $table->string('municipio');
        $table->string('endereco');
        $table->string('uf', 2);
        $table->string('complemento')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colaboradores');
    }
};
