<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class StudentDemoSeeder extends Seeder
{
    protected function nextReg(): string
    {
        $max = DB::table('students')->max('reg_no');
        $n   = $max ? (int)$max : 99999;
        return str_pad($n + 1, 6, '0', STR_PAD_LEFT);
    }

    public function run(): void
    {
        $creator = User::where('email', 'a@a')->first();
        if (!$creator) {
            $creator = User::first() ?? User::create([
                'name' => 'Admin User',
                'email' => 'a@a',
                'password' => Hash::make('a'),
            ]);
        }

        $demo = [
            [
                'name'         => 'Ali Ahmed',
                'email'        => 'student1@example.com',
                'password'     => 'password',
                'father_name'  => 'Ahmed Khan',
                'admission'    => Carbon::today()->subDays(10),
                'dob'          => Carbon::parse('2012-05-14'),
                'caste'        => 'Rajput',
                'parent_phone' => '0300-1111111',
                'guardian'     => '0301-2222222',
                'address'      => 'Street 1, Lahore',
            ],
            [
                'name'         => 'Fatima Noor',
                'email'        => 'student2@example.com',
                'password'     => 'password',
                'father_name'  => 'Noor Ali',
                'admission'    => Carbon::today()->subDays(7),
                'dob'          => Carbon::parse('2011-11-02'),
                'caste'        => 'Mughal',
                'parent_phone' => '0300-3333333',
                'guardian'     => '0301-4444444',
                'address'      => 'Street 2, Karachi',
            ],
            [
                'name'         => 'Hassan Raza',
                'email'        => 'student3@example.com',
                'password'     => 'password',
                'father_name'  => 'Raza Hussain',
                'admission'    => Carbon::today()->subDays(3),
                'dob'          => Carbon::parse('2013-03-22'),
                'caste'        => 'Syed',
                'parent_phone' => '0300-5555555',
                'guardian'     => '0301-6666666',
                'address'      => 'Street 3, Islamabad',
            ],
        ];

        // Add one fixed demo student for quick login
        $demo[] = [
            'name'         => 'Demo Student',
            'email'        => 'student@example.com',
            'password'     => 'password',
            'father_name'  => 'Demo Father',
            'admission'    => Carbon::today()->subDays(5),
            'dob'          => Carbon::parse('2012-01-01'),
            'caste'        => 'Demo',
            'parent_phone' => '0300-0000000',
            'guardian'     => '0301-0000000',
            'address'      => 'Demo Address',
        ];

        foreach ($demo as $d) {
            $studentUser = User::updateOrCreate(
                ['email' => $d['email']],
                ['name' => $d['name'], 'password' => Hash::make($d['password'])]
            );

            $existingReg = DB::table('students')
                ->where('student_id', $studentUser->id)
                ->value('reg_no');

            $reg = $existingReg ?: $this->nextReg();

            Student::updateOrCreate(
                ['student_id' => $studentUser->id],
                [
                    'user_id'           => $creator->id,
                    'class_id'          => null,
                    'course_id'         => null,
                    'reg_no'            => $reg,
                    'admission_date'    => $d['admission'],
                    'name'              => $d['name'],
                    'father_name'       => $d['father_name'],
                    'b_form'            => null,
                    'b_form_image_path' => null,
                    'dob'               => $d['dob'],
                    'caste'             => $d['caste'],
                    'parent_phone'      => $d['parent_phone'],
                    'guardian_phone'    => $d['guardian'],
                    'address'           => $d['address'],
                    'email'             => $d['email'],
                    'status'            => 1,
                ]
            );
        }
    }
}
