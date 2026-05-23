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
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id('id_peminjaman'); // [cite: 362, 456]
            $table->foreignId('id_user')->constrained('users', 'id_user')->onDelete('cascade'); // [cite: 457]
            $table->date('tanggal_pinjam'); // [cite: 363, 458]
            $table->date('tanggal_kembali')->nullable(); // [cite: 365, 459]
            $table->date('jatuh_tempo'); // [cite: 364, 460]
            $table->string('status_peminjaman')->default('dipinjam'); // dipinjam/kembali/telat [cite: 366, 461]
            $table->integer('denda')->default(0); // Antisipasi fitur denda di diagram [cite: 163, 421]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};
