<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Homework extends Model
{
    use HasFactory;

    // ✅ IMPORTANT: your migration creates "homeworks"
    protected $table = 'homeworks';

    protected $fillable = [
        'user_id',
        'class_id',
        'course_id',
        'subject_id',
        'file_path',
        'file_name',
        'comment',
        'day',
    ];

    protected $casts = [
        'day' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
