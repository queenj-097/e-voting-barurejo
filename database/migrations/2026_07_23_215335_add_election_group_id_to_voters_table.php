<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('voters', function (Blueprint $table) {
            $table->foreignId('election_group_id')
                ->nullable()
                ->after('address')
                ->constrained('election_groups')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('voters', function (Blueprint $table) {
            $table->dropConstrainedForeignId('election_group_id');
        });
    }
};