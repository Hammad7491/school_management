<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_name',
        'gender',
        'school_name',
        'class',
        'parent_name',
        'parent_contact',
        'parent_email',
    ];
}
