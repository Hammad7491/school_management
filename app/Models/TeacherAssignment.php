<?php
// app/Models/TeacherAssignment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'class_id',
        'assignment_type',
        'subject_id',
        'course_id',
        'designation',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    // Helper method to get the assigned item (subject or course)
    public function getAssignedItem()
    {
        return $this->assignment_type === 'subject' 
            ? $this->subject 
            : $this->course;
    }

    // Helper method to get assigned item name
    public function getAssignedItemName(): string
    {
        $item = $this->getAssignedItem();
        return $item ? $item->name : 'N/A';
    }
}