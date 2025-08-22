// database/migrations/2025_08_22_000000_create_monthly_reports_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('monthly_reports', function (Blueprint $table) {
            $table->id();

            // who created the report
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // optional links (for filtering / context)
            $table->foreignId('class_id')->nullable()->constrained('classes')->nullOnDelete();
            $table->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();

            // target student
            $table->string('reg_no');                 // 6-digit student reg no (text)
            $table->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();

            // report identity
            $table->date('report_date');              // any date within the month

            // snapshot fields (entered by teacher; also auto-filled when possible)
            $table->string('student_name');
            $table->string('father_name')->nullable();

            // optional narrative
            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->index(['reg_no', 'report_date']);
            $table->index(['class_id', 'course_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monthly_reports');
    }
};
