<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('election_settings', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('Pemungutan Suara Elektronik');
            $table->string('institution')->default('Desa Barurejo');
            $table->string('location')->nullable();
            $table->date('election_date')->nullable();
            $table->string('status')->default('persiapan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('election_settings');
    }
};