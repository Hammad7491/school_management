<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasRoles;

    protected string $guard_name = 'web';

    protected $fillable = ['name', 'email', 'password'];
    protected $hidden   = ['password', 'remember_token'];

    public function student(): HasOne
    {
        return $this->hasOne(Student::class, 'user_id');
    }

    public function teacherAssignments(): HasMany
    {
        return $this->hasMany(TeacherAssignment::class, 'teacher_id');
    }

    public function isTeacher(): bool
    {
        return $this->hasRole('Teacher');
    }

    public function activeAssignments()
    {
        return $this->teacherAssignments()->where('is_active', true);
    }

    public function getAssignedClassIds(): array
    {
        return $this->activeAssignments()
            ->pluck('class_id')
            ->filter()
            ->unique()
            ->values()
            ->toArray();
    }

    public function getAssignedSubjectIds(): array
    {
        return $this->activeAssignments()
            ->where('assignment_type', 'subject')
            ->pluck('subject_id')
            ->filter()
            ->unique()
            ->values()
            ->toArray();
    }

    public function getAssignedCourseIds(): array
    {
        return $this->activeAssignments()
            ->where('assignment_type', 'course')
            ->pluck('course_id')
            ->filter()
            ->unique()
            ->values()
            ->toArray();
    }

    // ✅ NEW: treat Teacher + Incharge as "staff"
    public function isStaff(): bool
    {
        return $this->hasAnyRole(['Teacher', 'Incharge']);
    }

    // ✅ NEW: active assignments collection helper (used in controllers)
    public function getActiveAssignments()
    {
        return $this->activeAssignments()->get();
    }
}
