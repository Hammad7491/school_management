<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        // who created the record (admin/school)
        'user_id',

        // relations
        'class_id',
        'course_id',

        // link to the user account of the student
        'student_id',

        // identifiers
        'reg_no',

        // profile
        'admission_date',
        'name',
        'father_name',
        'b_form',                // present in your migration
        'b_form_image_path',
        'dob',
        'caste',
        'parent_phone',
        'guardian_phone',
        'address',
        'email',                 // duplicated in students table per your migration

        // status
        'status',
    ];

    protected $casts = [
        'admission_date' => 'date',
        'dob'            => 'date',
        'status'         => 'integer', // or 'boolean' if you prefer
    ];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function account() // the student's user row
    {
        return $this->belongsTo(\App\Models\User::class, 'student_id');
    }

    public function createdBy() // the admin/school user who created the student
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
