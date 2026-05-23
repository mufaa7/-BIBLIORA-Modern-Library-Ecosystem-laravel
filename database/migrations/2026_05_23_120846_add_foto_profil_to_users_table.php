<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema; // << Perhatikan import-nya wajib Schema

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // FIX: Mengganti Route::table menjadi Schema::table
        Schema::table('users', function (Blueprint $table) {
            $table->string('foto_profil')->nullable()->after('status_aktif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // FIX: Mengganti Route::table menjadi Schema::table
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('foto_profil');
        });
    }
};