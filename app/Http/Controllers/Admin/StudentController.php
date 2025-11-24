<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with(['schoolClass','course','account'])
            ->orderBy('id','desc')
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
        // If a class/course is selected, treat it as "add_class/add_course = true"
        $request->merge([
            'add_class'  => $request->filled('class_id')  ? 1 : $request->input('add_class'),
            'add_course' => $request->filled('course_id') ? 1 : $request->input('add_course'),
        ]);

        $request->validate([
            'email'          => 'required|email|unique:users,email|unique:students,email',
            // name is now NOT unique, just required
            'name'           => 'required|string|max:150',
            'password'       => 'required|string|min:6',

            'father_name'    => 'required|string|max:150',
            'admission_date' => 'nullable|date',
            'dob'            => 'nullable|date',
            'caste'          => 'nullable|string|max:100',
            'parent_phone'   => 'nullable|string|max:30',
            'guardian_phone' => 'nullable|string|max:30',
            'address'        => 'nullable|string',

            'b_form_image'   => 'nullable|mimes:jpg,jpeg,png,webp,pdf|max:5120',
            'profile_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',

            'add_class'      => 'nullable|boolean',
            'add_course'     => 'nullable|boolean',
            'class_id'       => 'nullable|exists:classes,id',
            'course_id'      => 'nullable|exists:courses,id',
        ]);

        $addClass  = $request->boolean('add_class');
        $addCourse = $request->boolean('add_course');

        // Extra validation: if we say we are adding, class/course must be provided
        if ($addClass) {
            $request->validate(['class_id' => 'required|exists:classes,id']);
        }
        if ($addCourse) {
            $request->validate(['course_id' => 'required|exists:courses,id']);
        }

        return DB::transaction(function () use ($request, $addClass, $addCourse) {

            // 1) Create login user
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // 2) Generate new reg number
            $max  = Student::lockForUpdate()->max('reg_no');
            $next = str_pad(($max ? $max : 99999) + 1, 6, '0', STR_PAD_LEFT);

            // 3) Upload files
            $bformPath = $request->hasFile('b_form_image')
                ? $request->file('b_form_image')->store('bforms', 'public')
                : null;

            $profilePath = $request->hasFile('profile_image')
                ? $request->file('profile_image')->store('profiles', 'public')
                : null;

            // 4) Save student
            Student::create([
                // NOTE: if you want admin id here, change to auth()->id()
                'user_id'            => $user->id,
                'student_id'         => $user->id,

                'reg_no'             => $next,

                'class_id'           => $addClass  ? $request->class_id  : null,
                'course_id'          => $addCourse ? $request->course_id : null,

                'name'               => $request->name,
                'father_name'        => $request->father_name,
                'email'              => $request->email,

                'admission_date'     => $request->admission_date,
                'dob'                => $request->dob,
                'caste'              => $request->caste,
                'parent_phone'       => $request->parent_phone,
                'guardian_phone'     => $request->guardian_phone,
                'address'            => $request->address,

                'b_form_image_path'  => $bformPath,
                'profile_image_path' => $profilePath,

                'status'             => 1,
            ]);

            return redirect()
                ->route('students.index')
                ->with('success', 'Student added! Reg#: '.$next);
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

        // Same trick: if dropdown has value, consider add_class/add_course = true
        $request->merge([
            'add_class'  => $request->filled('class_id')  ? 1 : $request->input('add_class'),
            'add_course' => $request->filled('course_id') ? 1 : $request->input('add_course'),
        ]);

        $request->validate([
            'email'          => 'required|email|unique:users,email,' . $student->student_id
                                             . '|unique:students,email,' . $student->id,
            // name is now NOT unique on update either
            'name'           => 'required|string|max:150',
            'password'       => 'nullable|string|min:6',

            'father_name'    => 'required|string|max:150',
            'admission_date' => 'nullable|date',
            'dob'            => 'nullable|date',
            'caste'          => 'nullable|string|max:100',
            'parent_phone'   => 'nullable|string|max:30',
            'guardian_phone' => 'nullable|string|max:30',
            'address'        => 'nullable|string',

            'b_form_image'   => 'nullable|mimes:jpg,jpeg,png,webp,pdf|max:5120',
            'profile_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',

            'add_class'      => 'nullable|boolean',
            'add_course'     => 'nullable|boolean',
            'class_id'       => 'nullable|exists:classes,id',
            'course_id'      => 'nullable|exists:courses,id',

            'status'         => 'nullable|in:0,1',
        ]);

        $addClass  = $request->boolean('add_class');
        $addCourse = $request->boolean('add_course');

        if ($addClass) {
            $request->validate(['class_id' => 'required|exists:classes,id']);
        }
        if ($addCourse) {
            $request->validate(['course_id' => 'required|exists:courses,id']);
        }

        // Update B-form
        if ($request->hasFile('b_form_image')) {
            if ($student->b_form_image_path) {
                Storage::disk('public')->delete($student->b_form_image_path);
            }
            $student->b_form_image_path = $request->file('b_form_image')->store('bforms', 'public');
        }

        // Update profile image
        if ($request->hasFile('profile_image')) {
            if ($student->profile_image_path) {
                Storage::disk('public')->delete($student->profile_image_path);
            }
            $student->profile_image_path = $request->file('profile_image')->store('profiles', 'public');
        }

        // Update linked user
        $user = $student->account;
        if ($user) {
            $user->name  = $request->name;
            $user->email = $request->email;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();
        }

        // Update student
        $student->fill([
            'name'           => $request->name,
            'email'          => $request->email,
            'father_name'    => $request->father_name,
            'admission_date' => $request->admission_date,
            'dob'            => $request->dob,
            'caste'          => $request->caste,
            'parent_phone'   => $request->parent_phone,
            'guardian_phone' => $request->guardian_phone,
            'address'        => $request->address,

            'class_id'       => $addClass  ? $request->class_id  : null,
            'course_id'      => $addCourse ? $request->course_id : null,
        ]);

        if ($request->has('status')) {
            $student->status = $request->status;
        }

        $student->save();

        return redirect()->route('students.index')->with('success', 'Student updated!');
    }

    public function destroy($id)
    {
        $student = Student::with('account')->findOrFail($id);

        if ($student->b_form_image_path) {
            Storage::disk('public')->delete($student->b_form_image_path);
        }
        if ($student->profile_image_path) {
            Storage::disk('public')->delete($student->profile_image_path);
        }

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
            return back()->with('success', 'B-Form not found.');
        }

        return Storage::disk('public')->download($student->b_form_image_path);
    }
}
