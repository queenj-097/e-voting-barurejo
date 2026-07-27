<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('election_settings', function (Blueprint $table) {
            $table->string('candidate_scope')
                ->default('general')
                ->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('election_settings', function (Blueprint $table) {
            $table->dropColumn('candidate_scope');
        });
    }
};