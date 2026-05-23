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
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_user'); // Menyesuaikan laporan [cite: 333, 470]
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique()->nullable();
            $table->string('password');
            $table->string('no_telp')->nullable(); // [cite: 338, 472]
            $table->text('alamat')->nullable(); // [cite: 335]
            $table->enum('role', ['admin', 'user'])->default('user'); // Integrasi Role 
            $table->boolean('status_aktif')->default(true); // [cite: 341]
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
