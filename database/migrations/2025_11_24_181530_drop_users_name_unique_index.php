<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the unique index on the "name" column
            // Index name comes from the error: users_name_unique
            $table->dropUnique('users_name_unique');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Restore the unique index if you ever rollback
            $table->unique('name');
        });
    }
};
