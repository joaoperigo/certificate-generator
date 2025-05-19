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
        Schema::table('certificate_students', function (Blueprint $table) {
            $table->integer('quantity_hours_online')->nullable()->after('quantity_hours');
            $table->integer('quantity_hours_presential')->nullable()->after('quantity_hours_online');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificate_students', function (Blueprint $table) {
            $table->dropColumn(['quantity_hours_online', 'quantity_hours_presential']);
        });
    }
};
