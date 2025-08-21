<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homeworks', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('user_id')
                  ->constrained()              // references users.id
                  ->cascadeOnDelete();         // delete homework if user is deleted

            $table->foreignId('class_id')
                  ->nullable()
                  ->constrained('classes')     // references classes.id
                  ->nullOnDelete();            // set null if class is deleted

            $table->foreignId('course_id')
                  ->nullable()
                  ->constrained('courses')     // references courses.id
                  ->nullOnDelete();            // set null if course is deleted

            // File metadata
            $table->string('file_path')->nullable();  // e.g., homeworks/abc.pdf (storage path)
            $table->string('file_name')->nullable();  // original filename for display

            // Content
            $table->text('comment')->nullable();
            $table->date('day');                      // the date/day for the homework

            $table->timestamps();

            // Helpful indexes
            $table->index('day');
            $table->index(['class_id', 'course_id']);
            $table->index(['class_id', 'course_id', 'day']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homeworks');
    }
};
