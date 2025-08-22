<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'class_id', 'course_id',
        'reg_no', 'student_id',
        'report_date', 'student_name', 'father_name',
        'remarks',
    ];

    protected $casts = [
        'report_date' => 'date',
    ];

    public function creator()     { return $this->belongsTo(User::class, 'user_id'); }
    public function schoolClass() { return $this->belongsTo(SchoolClass::class, 'class_id'); }
    public function course()      { return $this->belongsTo(Course::class, 'course_id'); }
    public function student()     { return $this->belongsTo(Student::class, 'student_id'); }
}
