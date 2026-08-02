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
        Schema::create('voters', function (Blueprint $table) {
            $table->id();

            // Contoh: KRJ-01-03-001
            $table->string('voter_code')->unique();

            $table->string('name');
            $table->enum('gender', ['L', 'P']);

            $table->foreignId('dusun_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('rw', 3);
            $table->string('rt', 3);

            // Tidak semua data DPT memiliki NIK
            $table->string('nik')->nullable();

            // Diisi otomatis berdasarkan kelompok dusun
            $table->foreignId('election_group_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->boolean('has_voted')->default(false);
            $table->timestamp('voted_at')->nullable();

            $table->timestamps();

            // Mempercepat pencarian nomor urut dalam RT yang sama
            $table->index(['dusun_id', 'rw', 'rt']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voters');
    }
};