<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // ---- Safely drop legacy 'subject' column (if present) ----
        if (Schema::hasColumn('exam_results', 'subject')) {
            try {
                Schema::table('exam_results', function (Blueprint $table) {
                    $table->dropColumn('subject');
                });
            } catch (\Throwable $e) {
                // If MySQL still complains (e.g., due to residual metadata), ignore.
            }
        }

        // ---- Ensure subject_id exists and is FK to subjects ----
        if (!Schema::hasColumn('exam_results', 'subject_id')) {
            Schema::table('exam_results', function (Blueprint $table) {
                $table->foreignId('subject_id')
                      ->after('term_id')
                      ->constrained('subjects')
                      ->cascadeOnDelete();
            });
        }

        // ---- Ensure user_id (uploader) exists; nullable ----
        if (!Schema::hasColumn('exam_results', 'user_id')) {
            Schema::table('exam_results', function (Blueprint $table) {
                $table->foreignId('user_id')
                      ->nullable()
                      ->after('course_id')
                      ->constrained('users')
                      ->nullOnDelete();
            });
        }

        // ---- Ensure reg_no snapshot exists; nullable ----
        if (!Schema::hasColumn('exam_results', 'reg_no')) {
            Schema::table('exam_results', function (Blueprint $table) {
                $table->string('reg_no')->nullable()->after('user_id')->index();
            });
        }
    }

    public function down(): void
    {
        Schema::table('exam_results', function (Blueprint $table) {
            if (Schema::hasColumn('exam_results', 'reg_no')) {
                $table->dropColumn('reg_no');
            }
            if (Schema::hasColumn('exam_results', 'user_id')) {
                // For older Laravel versions:
                // $table->dropForeign(['user_id']);
                // $table->dropColumn('user_id');
                $table->dropConstrainedForeignId('user_id');
            }
            if (Schema::hasColumn('exam_results', 'subject_id')) {
                // $table->dropForeign(['subject_id']);
                // $table->dropColumn('subject_id');
                $table->dropConstrainedForeignId('subject_id');
            }
        });

        // We do NOT recreate the old 'subject' column
    }
};
