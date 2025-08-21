<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with(['schoolClass','course','account'])
            ->latest()
            ->paginate(10);

        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        $classes = SchoolClass::orderByRaw(
            "FIELD(name,'Play Group','Nursery','Prep','1','2','3','4','5','6','7','8','9','10')"
        )->get();
        $courses = Course::orderBy('name')->get();

        return view('admin.students.create', compact('classes','courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            // User (account) info
            'email'          => 'required|email|unique:users,email|unique:students,email',
            'name'           => 'required|string|max:150|unique:users,name',
            'password'       => 'required|string|min:6',

            // Student profile info
            'father_name'    => 'required|string|max:150',
            'admission_date' => 'nullable|date',
            'b_form_image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'dob'            => 'nullable|date',
            'caste'          => 'nullable|string|max:100',
            'parent_phone'   => 'nullable|string|max:30',
            'guardian_phone' => 'nullable|string|max:30',
            'address'        => 'nullable|string',

            // relations
            'add_class'      => 'nullable|boolean',
            'add_course'     => 'nullable|boolean',
            'class_id'       => 'nullable|exists:classes,id',
            'course_id'      => 'nullable|exists:courses,id',
        ]);

        if ($request->boolean('add_class')) {
            $request->validate(['class_id' => 'required|exists:classes,id']);
        }
        if ($request->boolean('add_course')) {
            $request->validate(['course_id' => 'required|exists:courses,id']);
        }

        return DB::transaction(function () use ($request) {

            // 1) Create the student's user account
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                // do NOT assign roles; students have no roles
            ]);

            // 2) Next 6-digit registration number
            $max    = Student::lockForUpdate()->max('reg_no');
            $maxInt = $max ? (int) $max : 99999;
            $next   = str_pad($maxInt + 1, 6, '0', STR_PAD_LEFT);

            // 3) Upload B-form image (optional)
            $bformPath = null;
            if ($request->hasFile('b_form_image')) {
                $bformPath = $request->file('b_form_image')->store('bforms', 'public');
            }

            // 4) Create the student profile (link student_id to created user)
            Student::create([
                'user_id'           => Auth::id(),                           // created by
                'class_id'          => $request->boolean('add_class')  ? $request->class_id  : null,
                'course_id'         => $request->boolean('add_course') ? $request->course_id : null,
                'student_id'        => $user->id,                           // <-- key link
                'reg_no'            => $next,
                'admission_date'    => $request->admission_date,
                'name'              => $request->name,
                'father_name'       => $request->father_name,
                'b_form_image_path' => $bformPath,
                'dob'               => $request->dob,
                'caste'             => $request->caste,
                'parent_phone'      => $request->parent_phone,
                'guardian_phone'    => $request->guardian_phone,
                'address'           => $request->address,
                'email'             => $request->email,                      // also stored here (your migration)
                'status'            => 1,                                     // approved when created by staff
            ]);

            return redirect()->route('students.index')->with('success', 'Student added! Reg#: '.$next);
        });
    }

    public function edit($id)
    {
        $student = Student::with('account')->findOrFail($id);
        $classes = SchoolClass::orderByRaw(
            "FIELD(name,'Play Group','Nursery','Prep','1','2','3','4','5','6','7','8','9','10')"
        )->get();
        $courses = Course::orderBy('name')->get();

        return view('admin.students.create', compact('student','classes','courses'));
    }

    public function update(Request $request, $id)
    {
        $student = Student::with('account')->findOrFail($id);

        $request->validate([
            // user email can be changed; must be unique except current
            'email'          => 'required|email|unique:users,email,' . $student->student_id . '|unique:students,email,' . $student->id,
            'name'           => 'required|string|max:150|unique:users,name,' . $student->student_id,
            'password'       => 'nullable|string|min:6',

            'father_name'    => 'required|string|max:150',
            'admission_date' => 'nullable|date',
            'b_form_image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'dob'            => 'nullable|date',
            'caste'          => 'nullable|string|max:100',
            'parent_phone'   => 'nullable|string|max:30',
            'guardian_phone' => 'nullable|string|max:30',
            'address'        => 'nullable|string',

            'add_class'      => 'nullable|boolean',
            'add_course'     => 'nullable|boolean',
            'class_id'       => 'nullable|exists:classes,id',
            'course_id'      => 'nullable|exists:courses,id',
            'status'         => 'nullable|in:0,1',
        ]);

        if ($request->boolean('add_class')) {
            $request->validate(['class_id' => 'required|exists:classes,id']);
        }
        if ($request->boolean('add_course')) {
            $request->validate(['course_id' => 'required|exists:courses,id']);
        }

        // 1) Update or replace B-form image
        if ($request->hasFile('b_form_image')) {
            if ($student->b_form_image_path && Storage::disk('public')->exists($student->b_form_image_path)) {
                Storage::disk('public')->delete($student->b_form_image_path);
            }
            $student->b_form_image_path = $request->file('b_form_image')->store('bforms', 'public');
        }

        // 2) Update linked user account (name, email, password)
        $user = $student->account;
        if ($user) {
            $user->name  = $request->name;
            $user->email = $request->email;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();
        }

        // 3) Update student profile
        $student->class_id       = $request->boolean('add_class')  ? $request->class_id  : null;
        $student->course_id      = $request->boolean('add_course') ? $request->course_id : null;
        $student->admission_date = $request->admission_date;
        $student->name           = $request->name;
        $student->father_name    = $request->father_name;
        $student->dob            = $request->dob;
        $student->caste          = $request->caste;
        $student->parent_phone   = $request->parent_phone;
        $student->guardian_phone = $request->guardian_phone;
        $student->address        = $request->address;
        $student->email          = $request->email;

        if ($request->has('status')) {
            $student->status = $request->input('status') === '' ? null : (int) $request->input('status');
        }
        $student->save();

        return redirect()->route('students.index')->with('success', 'Student updated!');
    }

    public function destroy($id)
    {
        $student = Student::with('account')->findOrFail($id);

        if ($student->b_form_image_path && Storage::disk('public')->exists($student->b_form_image_path)) {
            Storage::disk('public')->delete($student->b_form_image_path);
        }

        // delete the linked user account as well (optional; comment out if you prefer to keep user)
        if ($student->account) {
            $student->account->delete();
        }

        $student->delete();

        return redirect()->route('students.index')->with('success', 'Student deleted!');
    }

    public function downloadBForm($id)
    {
        $student = Student::findOrFail($id);
        if (!$student->b_form_image_path || !Storage::disk('public')->exists($student->b_form_image_path)) {
            return back()->with('success', 'B-Form image not found.');
        }
        return Storage::disk('public')->download($student->b_form_image_path);
    }
}
