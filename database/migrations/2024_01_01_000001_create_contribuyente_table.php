<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contribuyente', function (Blueprint $table) {
            $table->string('codigo', 20)->primary();
            $table->string('nombre', 150);
            $table->string('dni', 20)->nullable();
            $table->string('direccion', 200)->nullable();
            $table->string('telefono', 30)->nullable();
            $table->string('correo', 100)->nullable();
            $table->timestamps();

            $table->index('dni');
            $table->index('nombre');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contribuyente');
    }
};
