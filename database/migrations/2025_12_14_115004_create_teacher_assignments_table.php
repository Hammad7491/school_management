<?php
// database/migrations/2024_01_15_000002_create_teacher_assignments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id'); // references users.id
            $table->unsignedBigInteger('class_id');
            $table->enum('assignment_type', ['subject', 'course']);
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->enum('designation', ['incharge', 'subject_teacher']);
            $table->boolean('is_active')->default(1);
            $table->timestamps();

            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');

            // Ensure either subject_id or course_id is set, but not both
            $table->index(['teacher_id', 'class_id', 'assignment_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_assignments');
    }
};