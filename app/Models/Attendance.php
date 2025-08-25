<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendance';

    protected $fillable = [
        'student_id','term_id','class_id','course_id',
        'total_days','present_days','percentage'
    ];

    public function student(){ return $this->belongsTo(Student::class); }
    public function term(){ return $this->belongsTo(ExamTerm::class, 'term_id'); }
    public function schoolClass(){ return $this->belongsTo(SchoolClass::class, 'class_id'); }
    public function course(){ return $this->belongsTo(Course::class, 'course_id'); }
}
