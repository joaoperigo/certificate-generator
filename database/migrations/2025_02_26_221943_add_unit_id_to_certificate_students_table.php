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
         // Modify certificate_students table to replace unit string with foreign key
         Schema::table('certificate_students', function (Blueprint $table) {
            // First make the existing unit column nullable
            $table->string('unit')->nullable()->change();
            
            // Add the new unit_id column
            $table->foreignId('unit_id')->nullable()->after('certificate_id')->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove foreign key constraint and column
        Schema::table('certificate_students', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropColumn('unit_id');
        });
        
    }
};
