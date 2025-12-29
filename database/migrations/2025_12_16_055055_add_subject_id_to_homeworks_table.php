<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('homeworks', function (Blueprint $table) {
            // adjust table name if yours is different
            $table->foreignId('subject_id')
                ->nullable()
                ->after('course_id')
                ->constrained('subjects')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('homeworks', function (Blueprint $table) {
            $table->dropForeign(['subject_id']);
            $table->dropColumn('subject_id');
        });
    }
};
