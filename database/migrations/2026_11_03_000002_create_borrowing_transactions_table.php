<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borrowing_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('student_nisn');
            $table->string('student_name');
            $table->date('tanggal_pinjam');
            $table->date('batas_kembali');
            $table->enum('status_global', ['Aktif', 'Selesai Sebagian', 'Selesai Total'])->default('Aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrowing_transactions');
    }
};
