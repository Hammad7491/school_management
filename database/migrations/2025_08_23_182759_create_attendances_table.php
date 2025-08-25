<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('term_id')->constrained('exam_terms')->cascadeOnDelete();

            // Scope (one of them is set)
            $table->foreignId('class_id')->nullable()->constrained('classes')->nullOnDelete();
            $table->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();

            $table->unsignedInteger('total_days')->default(0);
            $table->unsignedInteger('present_days')->default(0);
            $table->decimal('percentage', 5, 2)->nullable();

            $table->timestamps();

            $table->unique(['student_id','term_id','class_id']);
            $table->unique(['student_id','term_id','course_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('attendance');
    }
};
