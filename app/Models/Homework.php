<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Homework extends Model
{
    use HasFactory;

    // Ensure we point to the correct table name
    protected $table = 'homeworks';

    protected $fillable = [
        'user_id',
        'class_id',
        'course_id',
        'file_path',
        'file_name',
        'comment',
        'day',
    ];

    protected $casts = [
        'day' => 'date',
    ];

    /**
     * Accessor: URL for the stored file (works if you use 'public' disk and ran storage:link)
     */
    public function getFileUrlAttribute(): ?string
    {
        return $this->file_path ? Storage::url($this->file_path) : null;
    }

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Avoid 'class()' as method name; keep 'schoolClass' (matches your code)
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
