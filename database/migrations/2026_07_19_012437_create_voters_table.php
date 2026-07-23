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
        $table->string('dpt_number')->unique();
        $table->string('nik')->unique();
        $table->string('name');
        $table->text('address')->nullable();
        $table->boolean('has_voted')->default(false);
        $table->timestamp('voted_at')->nullable();
        $table->timestamps();
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
