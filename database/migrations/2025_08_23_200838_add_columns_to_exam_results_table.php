<?php

// database/migrations/XXXX_XX_XX_XXXXXX_add_columns_to_exam_results_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('exam_results', function (Blueprint $table) {
            // if these columns already exist in your table, remove that line
            if (!Schema::hasColumn('exam_results', 'term_id')) {
                $table->foreignId('term_id')->constrained('exam_terms')->cascadeOnDelete();
            }
            if (!Schema::hasColumn('exam_results', 'subject_id')) {
                $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            }
            if (!Schema::hasColumn('exam_results', 'class_id')) {
                $table->foreignId('class_id')->nullable()->constrained('classes')->nullOnDelete();
            }
            if (!Schema::hasColumn('exam_results', 'course_id')) {
                $table->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();
            }

            if (!Schema::hasColumn('exam_results', 'exam_date')) {
                $table->date('exam_date')->nullable();
            }
            if (!Schema::hasColumn('exam_results', 'total_marks')) {
                $table->integer('total_marks');
            }
            if (!Schema::hasColumn('exam_results', 'obtained_marks')) {
                $table->integer('obtained_marks');
            }
            if (!Schema::hasColumn('exam_results', 'percentage')) {
                $table->decimal('percentage', 5, 2)->nullable();
            }
            if (!Schema::hasColumn('exam_results', 'grade')) {
                $table->string('grade')->nullable();
            }
            if (!Schema::hasColumn('exam_results', 'remarks')) {
                $table->text('remarks')->nullable();
            }

            // Unique key so one row per (student, term, subject, scope)
            $table->unique(
                ['student_id','term_id','subject_id','class_id','course_id'],
                'exam_results_unique_scope'
            );
        });
    }

    public function down(): void
    {
        Schema::table('exam_results', function (Blueprint $table) {
            // drop unique
            if (Schema::hasColumn('exam_results', 'student_id')) {
                $table->dropUnique('exam_results_unique_scope');
            }

            // drop FKs/columns only if they exist
            if (Schema::hasColumn('exam_results', 'course_id')) {
                $table->dropConstrainedForeignId('course_id');
            }
            if (Schema::hasColumn('exam_results', 'class_id')) {
                $table->dropConstrainedForeignId('class_id');
            }
            if (Schema::hasColumn('exam_results', 'subject_id')) {
                $table->dropConstrainedForeignId('subject_id');
            }
            if (Schema::hasColumn('exam_results', 'term_id')) {
                $table->dropConstrainedForeignId('term_id');
            }

            foreach (['exam_date','total_marks','obtained_marks','percentage','grade','remarks'] as $col) {
                if (Schema::hasColumn('exam_results', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

