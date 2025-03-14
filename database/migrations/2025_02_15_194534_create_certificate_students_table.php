<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificate_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certificate_id')->constrained()->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('code')->unique();
            $table->string('cpf')->nullable();
            $table->string('document')->nullable();
            $table->string('unit')->nullable();
            $table->string('course')->nullable();
            $table->integer('quantity_hours')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificate_students');
    }
};