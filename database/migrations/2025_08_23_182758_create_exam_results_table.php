<?php
// database/migrations/2025_08_23_182758_create_exam_results_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();

            // Who uploaded this result (staff)
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Term & scope
            $table->foreignId('term_id')->constrained('exam_terms')->cascadeOnDelete();

            // Class/Course scope (either can be used, or both null if you decide later)
            $table->foreignId('class_id')->nullable()->constrained('classes')->nullOnDelete();
            $table->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();

            // Student target (by reg_no; also optional link to students.id)
            $table->string('reg_no'); // 6-digit registration number as text
            $table->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();

            // SUBJECT AS TEXT (no FK to subjects to keep CSV flexible across classes)
            $table->string('subject', 150);

            // SUBJECT ID (optional FK to subjects table)
            $table->unsignedBigInteger('subject_id')->nullable();

            // Marks
            $table->unsignedInteger('total_marks');      // e.g., 100
            $table->unsignedInteger('obtained_marks');   // e.g., 85

            // Optional meta
            $table->date('exam_date')->nullable();

            // Attendance columns (optional; if you include in CSV)
            $table->unsignedSmallInteger('attendance_total')->nullable();   // e.g., 20
            $table->unsignedSmallInteger('attendance_present')->nullable(); // e.g., 18

            // Optional narrative
            $table->text('remarks')->nullable();

            $table->timestamps();

            // Foreign key for subject_id
            $table->foreign('subject_id')
                  ->references('id')
                  ->on('subjects')
                  ->nullOnDelete();

            // Helpful indexes
            $table->index(['term_id', 'class_id', 'course_id']);
            $table->index(['reg_no', 'term_id']);

            // Prevent duplicate rows for same student/term/subject/scope
            $table->unique(
                ['term_id', 'reg_no', 'subject', 'class_id', 'course_id'],
                'uniq_result_term_reg_subject_scope'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_results');
    }
};