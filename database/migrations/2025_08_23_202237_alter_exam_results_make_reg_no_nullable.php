<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasColumn('exam_results', 'reg_no')) {
            Schema::table('exam_results', function (Blueprint $table) {
                $table->string('reg_no')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('exam_results', 'reg_no')) {
            Schema::table('exam_results', function (Blueprint $table) {
                $table->string('reg_no')->nullable(false)->change();
            });
        }
    }
};
