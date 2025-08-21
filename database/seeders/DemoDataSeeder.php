<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Course;
use App\Models\Homework;

class DemoDataSeeder extends Seeder
{
    /**
     * Get next 6-digit registration number (global, string), starting at 100000.
     */
    protected function nextReg(): string
    {
        $max = Student::max('reg_no'); // e.g. "100007"
        $n   = $max ? (int)$max : 99999;
        return str_pad($n + 1, 6, '0', STR_PAD_LEFT);
    }

    public function run(): void
    {
        // Ensure public storage exists for demo files
        if (! Storage::disk('public')->exists('homeworks')) {
            Storage::disk('public')->makeDirectory('homeworks');
        }
        if (! Storage::disk('public')->exists('courses')) {
            Storage::disk('public')->makeDirectory('courses');
        }

        // Get staff users (Admin / Principal / Teacher). If roles not attached yet,
        // fallback to the known seeded emails.
        $staff = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['Admin', 'Principal', 'Teacher']);
        })->get();

        if ($staff->isEmpty()) {
            $fallbackEmails = [
                'a@a', 'principal@example.com', 'teacher@example.com'
            ];
            $staff = User::whereIn('email', $fallbackEmails)->get();
        }

        if ($staff->isEmpty()) {
            $this->command->warn('No staff users found. Run RolesAndPermissionsSeeder and UserSeeder first.');
            return;
        }

        foreach ($staff as $owner) {
            // ---------- Classes (3 per owner) ----------
            $classNames = ['Play Group', '1', '2']; // change as you like
            $classes = [];
            foreach ($classNames as $nm) {
                $classes[] = SchoolClass::updateOrCreate(
                    [
                        'user_id' => $owner->id,
                        'name'    => $nm,
                    ],
                    [
                        'fee'     => rand(500, 1500),
                        'status'  => 1,
                    ]
                );
            }

            // ---------- Courses (3 per owner) ----------
            $courseDefs = [
                ['name' => 'Web Development', 'fee' => 3000, 'desc' => 'HTML, CSS, JS basics'],
                ['name' => 'Graphic Design',  'fee' => 2500, 'desc' => 'Design principles & tools'],
                ['name' => 'Amazon Basics',   'fee' => 2800, 'desc' => 'Intro to Amazon selling'],
            ];
            $courses = [];
            foreach ($courseDefs as $i => $def) {
                $imgPath = "courses/demo_{$owner->id}_{$i}.jpg";
                if (!Storage::disk('public')->exists($imgPath)) {
                    Storage::disk('public')->put($imgPath, 'demo image bytes'); // small placeholder
                }

                $courses[] = Course::updateOrCreate(
                    [
                        'user_id' => $owner->id,
                        'name'    => $def['name'],
                    ],
                    [
                        'fee'         => $def['fee'],
                        'description' => $def['desc'],
                        'image_path'  => $imgPath,
                        'status'      => 1,
                    ]
                );
            }

            // ---------- Students (2 per owner) ----------
            $studentData = [
                [
                    'name' => "Student One ({$owner->name})",
                    'email'=> "stu1.{$owner->id}@example.com",
                    'father' => 'Father One',
                    'dob'    => '2012-02-10',
                    'caste'  => 'Rajput',
                    'pphone' => '0300-1111111',
                    'gphone' => '0301-2222222',
                    'addr'   => 'Street 1, City',
                ],
                [
                    'name' => "Student Two ({$owner->name})",
                    'email'=> "stu2.{$owner->id}@example.com",
                    'father' => 'Father Two',
                    'dob'    => '2011-07-22',
                    'caste'  => 'Mughal',
                    'pphone' => '0300-3333333',
                    'gphone' => '0301-4444444',
                    'addr'   => 'Street 2, City',
                ],
            ];

            foreach ($studentData as $sd) {
                // 1) Create/Update the student's account (users table)
                $stuUser = User::updateOrCreate(
                    ['email' => $sd['email']],
                    ['name' => $sd['name'], 'password' => Hash::make('password')]
                );

                // 2) Persist a stable reg_no across reseeds
                $existingReg = Student::where('student_id', $stuUser->id)->value('reg_no');
                $regNo = $existingReg ?: $this->nextReg();

                // 3) Create/Update the student profile (students table)
                Student::updateOrCreate(
                    ['student_id' => $stuUser->id],
                    [
                        'user_id'           => $owner->id,                 // created by owner
                        'class_id'          => $classes[0]->id ?? null,    // first class
                        'course_id'         => $courses[0]->id ?? null,    // first course
                        'reg_no'            => $regNo,
                        'admission_date'    => Carbon::today()->subDays(rand(3, 14)),
                        'name'              => $sd['name'],
                        'father_name'       => $sd['father'],
                        'b_form'            => null,
                        'b_form_image_path' => null,
                        'dob'               => Carbon::parse($sd['dob']),
                        'caste'             => $sd['caste'],
                        'parent_phone'      => $sd['pphone'],
                        'guardian_phone'    => $sd['gphone'],
                        'address'           => $sd['addr'],
                        'email'             => $sd['email'],               // duplicated by your schema
                        'status'            => 1,                           // approved by staff
                    ]
                );
            }

            // ---------- Homeworks (2 per owner) ----------
            // Make small downloadable placeholders
            $fileA = "homeworks/hw_class_{$owner->id}.pdf";
            $fileB = "homeworks/hw_course_{$owner->id}.pdf";
            if (!Storage::disk('public')->exists($fileA)) {
                Storage::disk('public')->put($fileA, 'Demo homework (class) PDF bytes');
            }
            if (!Storage::disk('public')->exists($fileB)) {
                Storage::disk('public')->put($fileB, 'Demo homework (course) PDF bytes');
            }

            // A) Class-based homework
            Homework::updateOrCreate(
                [
                    'user_id'  => $owner->id,
                    'class_id' => $classes[0]->id ?? null,
                    'course_id'=> null,
                    'day'      => Carbon::today()->subDays(1)->format('Y-m-d'),
                ],
                [
                    'file_path'=> $fileA,
                    'file_name'=> 'class_homework.pdf',
                    'comment'  => 'Read chapter 1 and answer Q1–Q5.',
                ]
            );

            // B) Course-based homework
            Homework::updateOrCreate(
                [
                    'user_id'  => $owner->id,
                    'class_id' => null,
                    'course_id'=> $courses[0]->id ?? null,
                    'day'      => Carbon::today()->format('Y-m-d'),
                ],
                [
                    'file_path'=> $fileB,
                    'file_name'=> 'course_homework.pdf',
                    'comment'  => 'Complete the first assignment of this course.',
                ]
            );
        }
    }
}

