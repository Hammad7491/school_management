<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            // relations
            $table->unsignedBigInteger('user_id');           // admin/school who created
            $table->unsignedBigInteger('class_id')->nullable();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->unsignedBigInteger('student_id')->nullable();

            // registration like previous roll no (6 digits, auto)
            $table->string('reg_no')->unique();

            // core fields
            $table->date('admission_date')->nullable();  // Date
            $table->string('name');                      // Name
            $table->string('father_name');               // Father Name

            $table->string('b_form')->nullable();        // B-Form number (text)
            $table->string('b_form_image_path')->nullable(); // B-Form image (this update)

            $table->date('dob')->nullable();             // D.O.B
            $table->string('caste')->nullable();         // Caste
            $table->string('parent_phone')->nullable();  // Parents Number
            $table->string('guardian_phone')->nullable();// Guardian Number
            $table->text('address')->nullable();         // Address

            // auth & status
            $table->string('email')->unique();                  // hashed
            $table->boolean('status')->nullable();       // null=pending, 1=approved, 0=rejected

            $table->timestamps();

            // FKs
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('set null');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
