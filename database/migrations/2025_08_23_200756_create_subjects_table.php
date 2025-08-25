<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Prevent duplicate table creation
        if (Schema::hasTable('subjects')) {
            return; // Table already exists, skip migration
        }

        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Each subject name must be unique
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Drop only if it exists
        if (Schema::hasTable('subjects')) {
            Schema::dropIfExists('subjects');
        }
    }
};
