<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booths', function (Blueprint $table) {
            $table->id();

            $table->string('name')->unique();

            $table->string('status')
                ->default('idle');

            $table->foreignId('current_voter_id')
                ->nullable()
                ->constrained('voters')
                ->nullOnDelete();

            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('voting_started_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booths');
    }
};