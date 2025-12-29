<?php
// app/Http/Controllers/Admin/StudentController.php

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
    private function isStaff($user): bool
    {
        return $user->hasAnyRole(['Teacher', 'Incharge']);
    }

    public function index()
    {
        $user = auth()->user();

        $query = Student::with(['schoolClass', 'course', 'account']);

        // ✅ Teacher OR Incharge => show union of ALL active assignments
        if ($this->isStaff($user)) {
            $assignments = $user->teacherAssignments()
                ->where('is_active', true)
                ->get();

            if ($assignments->isEmpty()) {
                $students = Student::whereRaw('1=0')->paginate(10);
            } else {
                $query->where(function ($q) use ($assignments) {
                    foreach ($assignments as $assignment) {
                        $q->orWhere(function ($subQ) use ($assignment) {
                            $subQ->where('class_id', $assignment->class_id);

                            // if course assignment => restrict to that course
                            if ($assignment->assignment_type === 'course' && $assignment->course_id) {
                                $subQ->where('course_id', $assignment->course_id);
                            }
                        });
                    }
                });

                $students = $query->orderByDesc('id')->paginate(10);
            }
        } else {
            $students = $query->orderByDesc('id')->paginate(10);
        }

        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        $user = auth()->user();

        if ($this->isStaff($user)) {
            $classIds  = $user->getAssignedClassIds();
            $courseIds = $user->getAssignedCourseIds();

            $classes = SchoolClass::whereIn('id', $classIds)
                ->orderByRaw("FIELD(name,'Play Group','Nursery','Prep','1','2','3','4','5','6','7','8','9','10')")
                ->get();

            $courses = Course::whereIn('id', $courseIds)->orderBy('name')->get();
        } else {
            $classes = SchoolClass::orderByRaw(
                "FIELD(name,'Play Group','Nursery','Prep','1','2','3','4','5','6','7','8','9','10')"
            )->get();

            $courses = Course::orderBy('name')->get();
        }

        return view('admin.students.create', compact('classes', 'courses'));
    }

    public function store(Request $request)
    {
        $authUser = auth()->user();

        if ($authUser->hasRole('Teacher') && !$authUser->can('create students')) {
            abort(403, 'Unauthorized action.');
        }

        $request->merge([
            'add_class'  => $request->filled('class_id')  ? 1 : $request->input('add_class'),
            'add_course' => $request->filled('course_id') ? 1 : $request->input('add_course'),
        ]);

        $request->validate([
            'email'          => 'required|email|unique:users,email|unique:students,email',
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

        if ($addClass)  $request->validate(['class_id'  => 'required|exists:classes,id']);
        if ($addCourse) $request->validate(['course_id' => 'required|exists:courses,id']);

        if ($this->isStaff($authUser)) {
            $assignedClassIds = $authUser->getAssignedClassIds();

            if ($addClass && !in_array((int)$request->class_id, $assignedClassIds, true)) {
                return back()->withErrors(['class_id' => 'You can only add students to your assigned classes.'])->withInput();
            }
        }

        return DB::transaction(function () use ($request, $addClass, $addCourse) {

            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $max  = Student::lockForUpdate()->max('reg_no');
            $next = str_pad(($max ? $max : 99999) + 1, 6, '0', STR_PAD_LEFT);

            $bformPath = $request->hasFile('b_form_image')
                ? $request->file('b_form_image')->store('bforms', 'public')
                : null;

            $profilePath = $request->hasFile('profile_image')
                ? $request->file('profile_image')->store('profiles', 'public')
                : null;

            Student::create([
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
                ->with('success', 'Student added! Reg#: ' . $next);
        });
    }

    public function edit($id)
    {
        $authUser = auth()->user();
        $student = Student::with('account')->findOrFail($id);

        if ($this->isStaff($authUser)) {
            $assignedClassIds = $authUser->getAssignedClassIds();
            if (!in_array((int)$student->class_id, $assignedClassIds, true)) {
                abort(403, 'You can only edit students from your assigned classes.');
            }
        }

        if ($this->isStaff($authUser)) {
            $classIds  = $authUser->getAssignedClassIds();
            $courseIds = $authUser->getAssignedCourseIds();

            $classes = SchoolClass::whereIn('id', $classIds)
                ->orderByRaw("FIELD(name,'Play Group','Nursery','Prep','1','2','3','4','5','6','7','8','9','10')")
                ->get();

            $courses = Course::whereIn('id', $courseIds)->orderBy('name')->get();
        } else {
            $classes = SchoolClass::orderByRaw(
                "FIELD(name,'Play Group','Nursery','Prep','1','2','3','4','5','6','7','8','9','10')"
            )->get();

            $courses = Course::orderBy('name')->get();
        }

        return view('admin.students.create', compact('student', 'classes', 'courses'));
    }

    public function update(Request $request, $id)
    {
        $authUser = auth()->user();
        $student = Student::with('account')->findOrFail($id);

        if ($this->isStaff($authUser)) {
            $assignedClassIds = $authUser->getAssignedClassIds();
            if (!in_array((int)$student->class_id, $assignedClassIds, true)) {
                abort(403, 'You can only edit students from your assigned classes.');
            }
        }

        $request->merge([
            'add_class'  => $request->filled('class_id')  ? 1 : $request->input('add_class'),
            'add_course' => $request->filled('course_id') ? 1 : $request->input('add_course'),
        ]);

        $request->validate([
            'email'          => 'required|email|unique:users,email,' . $student->student_id . '|unique:students,email,' . $student->id,
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

        if ($addClass)  $request->validate(['class_id'  => 'required|exists:classes,id']);
        if ($addCourse) $request->validate(['course_id' => 'required|exists:courses,id']);

        if ($request->hasFile('b_form_image')) {
            if ($student->b_form_image_path) {
                Storage::disk('public')->delete($student->b_form_image_path);
            }
            $student->b_form_image_path = $request->file('b_form_image')->store('bforms', 'public');
        }

        if ($request->hasFile('profile_image')) {
            if ($student->profile_image_path) {
                Storage::disk('public')->delete($student->profile_image_path);
            }
            $student->profile_image_path = $request->file('profile_image')->store('profiles', 'public');
        }

        $userAccount = $student->account;
        if ($userAccount) {
            $userAccount->name  = $request->name;
            $userAccount->email = $request->email;

            if ($request->filled('password')) {
                $userAccount->password = Hash::make($request->password);
            }
            $userAccount->save();
        }

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
        $authUser = auth()->user();
        $student = Student::with('account')->findOrFail($id);

        if ($this->isStaff($authUser)) {
            $assignedClassIds = $authUser->getAssignedClassIds();
            if (!in_array((int)$student->class_id, $assignedClassIds, true)) {
                abort(403, 'You can only delete students from your assigned classes.');
            }
        }

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

    // ✅ Download B-Form with proper filename
    public function downloadBForm($id)
    {
        $student = Student::findOrFail($id);

        if (!$student->b_form_image_path || !Storage::disk('public')->exists($student->b_form_image_path)) {
            return back()->with('error', 'B-Form not found.');
        }

        $name = 'bform_' . ($student->reg_no ?? $student->id) . '.' . pathinfo($student->b_form_image_path, PATHINFO_EXTENSION);

        return Storage::disk('public')->download($student->b_form_image_path, $name);
    }

    // ✅ NEW: Download Profile Photo with proper filename
    public function downloadProfilePhoto($id)
    {
        $student = Student::findOrFail($id);

        if (!$student->profile_image_path || !Storage::disk('public')->exists($student->profile_image_path)) {
            return back()->with('error', 'Profile photo not found.');
        }

        $name = 'profile_' . ($student->reg_no ?? $student->id) . '.' . pathinfo($student->profile_image_path, PATHINFO_EXTENSION);

        return Storage::disk('public')->download($student->profile_image_path, $name);
    }
}
