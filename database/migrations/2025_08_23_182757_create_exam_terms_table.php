<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('exam_terms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();           // e.g. "Midterm 2025"
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('exam_terms');
    }
};
