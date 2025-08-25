<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    protected $fillable = [
        'user_id',
        'student_id','term_id','subject_id',
        'class_id','course_id',
        'reg_no',
        'exam_date','total_marks','obtained_marks',
        'percentage','grade','remarks',

        // Keep this in the fillable list so code works whether the column exists or not.
        // If your table no longer has a `subject` text column, this key is simply ignored.
        'subject',
    ];

    public function student()     { return $this->belongsTo(Student::class); }
    public function term()        { return $this->belongsTo(ExamTerm::class, 'term_id'); }
    public function subject()     { return $this->belongsTo(Subject::class); }
    public function schoolClass() { return $this->belongsTo(SchoolClass::class, 'class_id'); }
    public function course()      { return $this->belongsTo(Course::class, 'course_id'); }
    public function uploader()    { return $this->belongsTo(User::class, 'user_id'); }
}
