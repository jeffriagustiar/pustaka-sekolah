<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guest_visits', function (Blueprint $table) {
            $table->id();
            $table->enum('visitor_type', ['siswa', 'guru']);
            $table->string('identifier');
            $table->string('nama');
            $table->string('kelas')->nullable();
            $table->timestamp('visited_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guest_visits');
    }
};
