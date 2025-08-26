<?php

// database/migrations/2025_08_23_000000_create_vacation_requests_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('vacation_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->string('reg_no');
            $table->string('student_name');
            $table->foreignId('class_id')->nullable()->constrained('classes')->nullOnDelete();
            $table->text('reason');
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('vacation_requests');
    }
};

