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
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('titulo');
            $table->text('descripcion')->nullable();

            $table->enum('estado', [
                'Pendiente',
                'En progreso',
                'Completada'
            ])->default('Pendiente');

            $table->date('fecha_limite')->nullable();

            $table->enum('prioridad', [
                'Baja',
                'Media',
                'Alta'
            ])->default('Media');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tareas');
    }
};