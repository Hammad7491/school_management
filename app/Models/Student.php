<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','class_id','course_id',
        'reg_no','admission_date','name','father_name','b_form_image_path',
        'dob','caste','parent_phone','guardian_phone','address',
        'password','status',
    ];

    // auto-hash password
    public function setPasswordAttribute($value)
    {
        if ($value && !str_starts_with((string)$value, '$2y$')) {
            $this->attributes['password'] = Hash::make($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
