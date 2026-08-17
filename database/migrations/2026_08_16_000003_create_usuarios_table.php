<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_contribuyente', 20)->unique();
            $table->string('dni', 20)->unique();
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->dateTime('email_verified_at')->nullable();
            $table->string('codigo_verificacion');
            $table->dateTime('codigo_expira_en');
            $table->timestamps();

            $table->foreign('codigo_contribuyente')->references('codigo')->on('contribuyente')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
