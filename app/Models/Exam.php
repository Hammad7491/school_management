<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'class_id', 'course_id',
        'file_path', 'file_name', 'comment',
        'day',
    ];

    protected $casts = [
        'day' => 'date',
    ];

    public function schoolClass()
    {
        return $this->belongsTo(\App\Models\SchoolClass::class, 'class_id');
    }

    public function course()
    {
        return $this->belongsTo(\App\Models\Course::class, 'course_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
