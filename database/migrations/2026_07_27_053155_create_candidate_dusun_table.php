<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidate_dusun', function (Blueprint $table) {

            $table->id();

            $table->foreignId('candidate_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('dusun_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unique([
                'candidate_id',
                'dusun_id'
            ]);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidate_dusun');
    }
};