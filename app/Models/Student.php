<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','class_id','course_id','student_id','reg_no','admission_date',
        'name','father_name','b_form','b_form_image_path','profile_image_path',
        'dob','caste','parent_phone','guardian_phone','address','email','status',
    ];

    protected $casts = [
        'admission_date' => 'date',
        'dob'            => 'date',
        'status'         => 'integer',
    ];

    protected $appends = ['profile_image_url'];

    /**
     * Normalize stored path so it always becomes: profiles/xxx.jpg
     */
    private function normalizePath(?string $path): ?string
    {
        if (!$path) return null;

        $path = str_replace('\\', '/', trim($path));    // windows slashes
        $path = ltrim($path, '/');                      // remove leading /
        $path = preg_replace('#^public/#', '', $path);  // remove "public/"
        $path = preg_replace('#^storage/#', '', $path); // remove "storage/"

        return $path ?: null;
    }

    public function getProfileImageUrlAttribute(): string
    {
        $path = $this->normalizePath($this->profile_image_path);

        // ✅ This uses current request host + port (127.0.0.1:8000), NOT .env APP_URL
        if ($path) {
            return asset('storage/' . $path); // /storage/profiles/...
        }

        // ✅ no file? use inline fallback (no need of png file)
        return 'data:image/svg+xml;utf8,' . rawurlencode(
            '<svg xmlns="http://www.w3.org/2000/svg" width="160" height="160">
              <defs>
                <linearGradient id="g" x1="0" y1="0" x2="1" y2="1">
                  <stop stop-color="#6a7bff" offset="0"/>
                  <stop stop-color="#22d3ee" offset="1"/>
                </linearGradient>
              </defs>
              <rect width="100%" height="100%" rx="80" fill="url(#g)"/>
              <text x="50%" y="54%" dominant-baseline="middle" text-anchor="middle"
                    font-family="Arial" font-size="64" font-weight="900" fill="white">S</text>
            </svg>'
        );
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function account()
    {
        return $this->belongsTo(\App\Models\User::class, 'student_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
