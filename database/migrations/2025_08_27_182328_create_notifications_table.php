<?php

// database/migrations/2025_08_27_120000_create_notifications_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->string('title', 200);
            $table->text('body');
            $table->timestamp('published_at')->nullable(); // set when shown to students
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('notifications');
    }
};

