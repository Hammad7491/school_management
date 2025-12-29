<?php
// app/Models/SchoolClass.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolClass extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = ['user_id', 'name', 'fee', 'status'];

    public function teacherAssignments(): HasMany
    {
        return $this->hasMany(TeacherAssignment::class, 'class_id');
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'teacher_assignments', 'class_id', 'teacher_id')
                    ->where('is_active', true);
    }
}