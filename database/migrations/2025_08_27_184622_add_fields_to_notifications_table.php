<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Guard against re-adding if columns already exist
        if (!Schema::hasColumn('notifications', 'is_active')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->boolean('is_active')->default(false)->after('body');
            });
        }

        if (!Schema::hasColumn('notifications', 'published_at')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->timestamp('published_at')->nullable()->after('is_active');
            });
        }

        if (!Schema::hasColumn('notifications', 'created_by')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->foreignId('created_by')
                      ->after('published_at')
                      ->constrained('users')
                      ->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            if (Schema::hasColumn('notifications', 'created_by')) {
                // drop FK first (name may vary per DB, so let Laravel infer)
                $table->dropConstrainedForeignId('created_by');
            }
            if (Schema::hasColumn('notifications', 'published_at')) {
                $table->dropColumn('published_at');
            }
            if (Schema::hasColumn('notifications', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};
