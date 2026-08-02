<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Kolom election_group_id sudah dibuat
        // langsung pada migration create_voters_table.
    }

    public function down(): void
    {
        // Tidak perlu melakukan apa pun.
    }
};