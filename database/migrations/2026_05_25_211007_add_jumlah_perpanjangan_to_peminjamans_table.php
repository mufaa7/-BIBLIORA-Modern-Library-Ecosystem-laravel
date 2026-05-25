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
        Schema::table('peminjamans', function (Blueprint $table) {
            // Suntik kolom baru bertipe integer dengan nilai default 0 tepat di bawah kolom denda
            $table->integer('jumlah_perpanjangan')->default(0)->after('denda');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            // Drop kolom jika migrasi di-rollback
            $table->dropColumn('jumlah_perpanjangan');
        });
    }
};