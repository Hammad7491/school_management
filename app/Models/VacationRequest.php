<?php

// app/Models/VacationRequest.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VacationRequest extends Model
{
   protected $fillable = [
  'student_id','class_id','reg_no','student_name',
  'status','reason','start_date','end_date',
];
protected $casts = [
  'start_date' => 'date',
  'end_date'   => 'date',
];


    public function student() {
        return $this->belongsTo(Student::class);
    }

    public function class() {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
}

