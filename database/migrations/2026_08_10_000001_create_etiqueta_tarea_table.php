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
        Schema::create('etiqueta_tarea', function (Blueprint $table) {
            $table->foreignId('etiqueta_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('tarea_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->primary(['etiqueta_id', 'tarea_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etiqueta_tarea');
    }
};
