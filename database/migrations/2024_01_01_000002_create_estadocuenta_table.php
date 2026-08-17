<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estadocuenta', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_contribuyente', 20);
            $table->integer('anio');
            $table->string('tipo_estado', 50)->nullable();
            $table->decimal('monto', 12, 2)->nullable();
            $table->decimal('importe', 12, 2)->nullable();
            $table->string('codigo_compuesto', 255)->nullable();
            $table->timestamps();

            $table->foreign('codigo_contribuyente')
                ->references('codigo')->on('contribuyente')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->index(['codigo_contribuyente', 'anio']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estadocuenta');
    }
};
