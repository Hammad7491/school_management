<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');      // who created it (admin/staff)
            $table->string('name');                     // course name
            $table->decimal('fee', 10, 2)->nullable();  // fee (nullable)
            $table->text('description')->nullable();    // description
            $table->string('image_path')->nullable();   // stored image path
            $table->boolean('status')->nullable();      // status will be NULL by default
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
