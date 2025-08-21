<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('class_id')->nullable()->constrained('classes')->nullOnDelete();
            $table->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();

            $table->string('file_path')->nullable(); // storage/app/public/exams/...
            $table->string('file_name')->nullable(); // original filename
            $table->text('comment')->nullable();

            $table->timestamps();

            $table->index(['class_id', 'course_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
