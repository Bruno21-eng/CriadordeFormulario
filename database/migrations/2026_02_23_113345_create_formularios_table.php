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
        Schema::create('formularios', function (Blueprint $table) {
            $table->id();
            // FK para saber de qual formulário essa resposta pertence
            $table->foreignId('Createformulario_id')->constrained('Createformularios')->onDelete('cascade');
            // Onde vamos salvar as respostas (ex: {"nome": "João", "idade": 25})
            $table->json('respostas')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formularios');
    }
};
