<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Homework;
use App\Models\SchoolClass;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HomeworkController extends Controller
{
    private function isStaff($user): bool
    {
        return $user && $user->hasAnyRole(['Teacher', 'Incharge']);
    }

    private function isStudent($user): bool
    {
        return $user && $user->hasRole('Student');
    }

    private function isAdminLike($user): bool
    {
        return $user && $user->hasAnyRole(['Admin', 'Principal', 'Super Admin']);
    }

    private function getLoggedInStudent($user): ?Student
    {
        if (!$user) return null;

        return Student::where('student_id', $user->id)
            ->orWhere('user_id', $user->id)
            ->first();
    }

    private function canAccessHomework($user, Homework $hw): bool
    {
        if ($this->isAdminLike($user)) return true;

        // Staff: only own created homework
        if ($this->isStaff($user)) {
            return (int)$hw->user_id === (int)$user->id;
        }

        // Student: only own class/course homework
        if ($this->isStudent($user)) {
            $student = $this->getLoggedInStudent($user);
            if (!$student) return false;

            $classId  = (int)($student->class_id ?? 0);
            $courseId = (int)($student->course_id ?? 0);

            return
                ($hw->class_id && (int)$hw->class_id === $classId) ||
                ($hw->course_id && $courseId && (int)$hw->course_id === $courseId);
        }

        return false;
    }

    public function index()
    {
        $user = auth()->user();

        $query = Homework::with(['schoolClass', 'course', 'subject', 'user']);

        // ✅ Student: show all homework of his class/course
        if ($this->isStudent($user)) {
            $student = $this->getLoggedInStudent($user);

            if (!$student) {
                $homeworks = Homework::whereRaw('1=0')->paginate(12);
            } else {
                $classId  = $student->class_id;
                $courseId = $student->course_id;

                $query->where(function ($q) use ($classId, $courseId) {
                    if (!empty($classId)) {
                        $q->orWhere('class_id', $classId);
                    }
                    if (!empty($courseId)) {
                        $q->orWhere('course_id', $courseId);
                    }
                });

                $homeworks = $query->orderByDesc('day')->latest('id')->paginate(12);
            }

            return view('admin.homeworks.index', compact('homeworks'));
        }

        // ✅ Teacher/Incharge: show ONLY own created homework
        if ($this->isStaff($user)) {
            $homeworks = $query
                ->where('user_id', $user->id)
                ->orderByDesc('day')
                ->latest('id')
                ->paginate(12);

            return view('admin.homeworks.index', compact('homeworks'));
        }

        // ✅ Admin/Principal: all
        $homeworks = $query->orderByDesc('day')->latest('id')->paginate(12);
        return view('admin.homeworks.index', compact('homeworks'));
    }

    public function create()
    {
        $user = auth()->user();

        if ($this->isStudent($user)) {
            abort(403, 'Students are not allowed to create homework.');
        }

        if ($this->isStaff($user)) {
            $classIds   = $user->getAssignedClassIds();
            $courseIds  = $user->getAssignedCourseIds();
            $subjectIds = $user->getAssignedSubjectIds();

            $classes = SchoolClass::whereIn('id', $classIds)
                ->orderByRaw("FIELD(name,'Play Group','Nursery','Prep','1','2','3','4','5','6','7','8','9','10')")
                ->get();

            $courses  = Course::whereIn('id', $courseIds)->orderBy('name')->get();
            $subjects = Subject::whereIn('id', $subjectIds)->where('status', 1)->orderBy('name')->get();
        } else {
            $classes = SchoolClass::orderByRaw("FIELD(name,'Play Group','Nursery','Prep','1','2','3','4','5','6','7','8','9','10')")->get();
            $courses = Course::orderBy('name')->get();
            $subjects = Subject::where('status', 1)->orderBy('name')->get();
        }

        return view('admin.homeworks.create', compact('classes', 'courses', 'subjects'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if ($this->isStudent($user)) {
            abort(403, 'Students are not allowed to create homework.');
        }

        $baseRules = [
            'day'        => 'required|date',
            'comment'    => 'nullable|string',
            'add_class'  => 'nullable|boolean',
            'add_course' => 'nullable|boolean',
            'class_id'   => 'nullable|exists:classes,id',
            'course_id'  => 'nullable|exists:courses,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'file'       => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
        ];

        // ✅ staff must select subject
        if ($this->isStaff($user)) {
            $baseRules['subject_id'] = 'required|exists:subjects,id';
        }

        $request->validate($baseRules);

        if (!$request->boolean('add_class') && !$request->boolean('add_course')) {
            return back()->withErrors(['add_class' => 'Select at least Class or Course.'])->withInput();
        }
        if ($request->boolean('add_class')) {
            $request->validate(['class_id' => 'required|exists:classes,id']);
        }
        if ($request->boolean('add_course')) {
            $request->validate(['course_id' => 'required|exists:courses,id']);
        }

        // ✅ Staff restrictions (class/course/subject must be assigned)
        if ($this->isStaff($user)) {
            $assignedClassIds   = $user->getAssignedClassIds();
            $assignedCourseIds  = $user->getAssignedCourseIds();
            $assignedSubjectIds = $user->getAssignedSubjectIds();

            if ($request->boolean('add_class') && !in_array((int)$request->class_id, $assignedClassIds, true)) {
                return back()->withErrors(['class_id' => 'You can only add homework to your assigned classes.'])->withInput();
            }

            if ($request->boolean('add_course') && !in_array((int)$request->course_id, $assignedCourseIds, true)) {
                return back()->withErrors(['course_id' => 'You can only add homework to your assigned courses.'])->withInput();
            }

            if (!in_array((int)$request->subject_id, $assignedSubjectIds, true)) {
                return back()->withErrors(['subject_id' => 'You can only add homework for your assigned subjects.'])->withInput();
            }
        }

        $path = null;
        $original = null;
        if ($request->hasFile('file')) {
            $original = $request->file('file')->getClientOriginalName();
            $path     = $request->file('file')->store('homeworks', 'public');
        }

        Homework::create([
            'user_id'    => Auth::id(),
            'class_id'   => $request->boolean('add_class')  ? $request->class_id  : null,
            'course_id'  => $request->boolean('add_course') ? $request->course_id : null,
            'subject_id' => $request->subject_id,
            'file_path'  => $path,
            'file_name'  => $original,
            'comment'    => $request->comment,
            'day'        => $request->day,
        ]);

        return redirect()->route('homeworks.index')->with('success', 'Homework created successfully.');
    }

    public function edit($id)
    {
        $user = auth()->user();
        $homework = Homework::findOrFail($id);

        if (!$this->canAccessHomework($user, $homework)) {
            abort(403, 'Unauthorized.');
        }

        if ($this->isStudent($user)) {
            abort(403, 'Students are not allowed to edit homework.');
        }

        if ($this->isStaff($user)) {
            $classIds   = $user->getAssignedClassIds();
            $courseIds  = $user->getAssignedCourseIds();
            $subjectIds = $user->getAssignedSubjectIds();

            $classes = SchoolClass::whereIn('id', $classIds)
                ->orderByRaw("FIELD(name,'Play Group','Nursery','Prep','1','2','3','4','5','6','7','8','9','10')")
                ->get();

            $courses  = Course::whereIn('id', $courseIds)->orderBy('name')->get();
            $subjects = Subject::whereIn('id', $subjectIds)->where('status', 1)->orderBy('name')->get();
        } else {
            $classes = SchoolClass::orderByRaw("FIELD(name,'Play Group','Nursery','Prep','1','2','3','4','5','6','7','8','9','10')")->get();
            $courses = Course::orderBy('name')->get();
            $subjects = Subject::where('status', 1)->orderBy('name')->get();
        }

        return view('admin.homeworks.create', compact('homework', 'classes', 'courses', 'subjects'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $hw = Homework::findOrFail($id);

        if (!$this->canAccessHomework($user, $hw)) {
            abort(403, 'Unauthorized.');
        }

        if ($this->isStudent($user)) {
            abort(403, 'Students are not allowed to update homework.');
        }

        $baseRules = [
            'day'        => 'required|date',
            'comment'    => 'nullable|string',
            'add_class'  => 'nullable|boolean',
            'add_course' => 'nullable|boolean',
            'class_id'   => 'nullable|exists:classes,id',
            'course_id'  => 'nullable|exists:courses,id', // ✅ FIXED (was classes)
            'subject_id' => 'nullable|exists:subjects,id',
            'file'       => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
        ];

        // ✅ staff must select subject
        if ($this->isStaff($user)) {
            $baseRules['subject_id'] = 'required|exists:subjects,id';
        }

        $request->validate($baseRules);

        if (!$request->boolean('add_class') && !$request->boolean('add_course')) {
            return back()->withErrors(['add_class' => 'Select at least Class or Course.'])->withInput();
        }
        if ($request->boolean('add_class')) {
            $request->validate(['class_id' => 'required|exists:classes,id']);
        }
        if ($request->boolean('add_course')) {
            $request->validate(['course_id' => 'required|exists:courses,id']);
        }

        if ($this->isStaff($user)) {
            $assignedClassIds   = $user->getAssignedClassIds();
            $assignedCourseIds  = $user->getAssignedCourseIds();
            $assignedSubjectIds = $user->getAssignedSubjectIds();

            if ($request->boolean('add_class') && !in_array((int)$request->class_id, $assignedClassIds, true)) {
                return back()->withErrors(['class_id' => 'You can only assign homework to your assigned classes.'])->withInput();
            }
            if ($request->boolean('add_course') && !in_array((int)$request->course_id, $assignedCourseIds, true)) {
                return back()->withErrors(['course_id' => 'You can only assign homework to your assigned courses.'])->withInput();
            }
            if (!in_array((int)$request->subject_id, $assignedSubjectIds, true)) {
                return back()->withErrors(['subject_id' => 'You can only assign homework to your assigned subjects.'])->withInput();
            }
        }

        if ($request->hasFile('file')) {
            if ($hw->file_path && Storage::disk('public')->exists($hw->file_path)) {
                Storage::disk('public')->delete($hw->file_path);
            }
            $hw->file_name = $request->file('file')->getClientOriginalName();
            $hw->file_path = $request->file('file')->store('homeworks', 'public');
        }

        $hw->class_id   = $request->boolean('add_class')  ? $request->class_id  : null;
        $hw->course_id  = $request->boolean('add_course') ? $request->course_id : null;
        $hw->subject_id = $request->subject_id;
        $hw->comment    = $request->comment;
        $hw->day        = $request->day;
        $hw->save();

        return redirect()->route('homeworks.index')->with('success', 'Homework updated successfully.');
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $hw = Homework::findOrFail($id);

        if (!$this->canAccessHomework($user, $hw)) {
            abort(403, 'Unauthorized.');
        }

        if ($this->isStudent($user)) {
            abort(403, 'Students are not allowed to delete homework.');
        }

        if ($hw->file_path && Storage::disk('public')->exists($hw->file_path)) {
            Storage::disk('public')->delete($hw->file_path);
        }

        $hw->delete();

        return redirect()->route('homeworks.index')->with('success', 'Homework deleted successfully.');
    }

    public function download($id)
    {
        $user = auth()->user();
        $hw = Homework::findOrFail($id);

        if (!$this->canAccessHomework($user, $hw)) {
            abort(403, 'Unauthorized.');
        }

        if (!$hw->file_path || !Storage::disk('public')->exists($hw->file_path)) {
            return back()->with('error', 'File not found.');
        }

        $name = $hw->file_name ?: basename($hw->file_path);
        return Storage::disk('public')->download($hw->file_path, $name);
    }
}
