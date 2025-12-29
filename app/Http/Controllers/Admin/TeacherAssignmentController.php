<?php
// app/Http/Controllers/Admin/TeacherAssignmentController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeacherAssignment;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherAssignmentController extends Controller
{
    public function index()
    {
        $assignments = TeacherAssignment::with(['teacher', 'schoolClass', 'subject', 'course'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.teacher-assignments.index', compact('assignments'));
    }

    public function create()
    {
        // Get users with Teacher OR Incharge role
        $teachers = User::whereHas('roles', function($query) {
            $query->whereIn('name', ['Teacher', 'Incharge']);
        })->orderBy('name')->get();

        $classes = SchoolClass::orderByRaw(
            "FIELD(name,'Play Group','Nursery','Prep','1','2','3','4','5','6','7','8','9','10')"
        )->get();
        $subjects = Subject::where('status', 1)->orderBy('name')->get();
        $courses = Course::orderBy('name')->get();

        return view('admin.teacher-assignments.create', compact('teachers', 'classes', 'subjects', 'courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'class_id' => 'required|exists:classes,id',
            'designation' => 'required|in:incharge,subject_teacher',
            'assignment_type' => 'required|in:subject,course',
            'subject_id' => 'required_if:assignment_type,subject|nullable|exists:subjects,id',
            'course_id' => 'required_if:assignment_type,course|nullable|exists:courses,id',
        ]);

        // Verify the user has Teacher or Incharge role
        $teacher = User::findOrFail($request->teacher_id);
        if (!$teacher->hasAnyRole(['Teacher', 'Incharge'])) {
            return back()->withErrors(['teacher_id' => 'Selected user must have Teacher or Incharge role.']);
        }

        DB::transaction(function () use ($request) {
            TeacherAssignment::create([
                'teacher_id' => $request->teacher_id,
                'class_id' => $request->class_id,
                'assignment_type' => $request->assignment_type,
                'subject_id' => $request->assignment_type === 'subject' ? $request->subject_id : null,
                'course_id' => $request->assignment_type === 'course' ? $request->course_id : null,
                'designation' => $request->designation,
                'is_active' => true,
            ]);
        });

        return redirect()->route('admin.teacher-assignments.index')
            ->with('success', 'Teacher assignment created successfully!');
    }

    public function edit($id)
    {
        $assignment = TeacherAssignment::findOrFail($id);
        
        $teachers = User::whereHas('roles', function($query) {
            $query->whereIn('name', ['Teacher', 'Incharge']);
        })->orderBy('name')->get();

        $classes = SchoolClass::orderByRaw(
            "FIELD(name,'Play Group','Nursery','Prep','1','2','3','4','5','6','7','8','9','10')"
        )->get();
        $subjects = Subject::where('status', 1)->orderBy('name')->get();
        $courses = Course::orderBy('name')->get();

        return view('admin.teacher-assignments.create', compact('assignment', 'teachers', 'classes', 'subjects', 'courses'));
    }

    public function update(Request $request, $id)
    {
        $assignment = TeacherAssignment::findOrFail($id);

        $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'class_id' => 'required|exists:classes,id',
            'designation' => 'required|in:incharge,subject_teacher',
            'assignment_type' => 'required|in:subject,course',
            'subject_id' => 'required_if:assignment_type,subject|nullable|exists:subjects,id',
            'course_id' => 'required_if:assignment_type,course|nullable|exists:courses,id',
            'is_active' => 'nullable|boolean',
        ]);

        $teacher = User::findOrFail($request->teacher_id);
        if (!$teacher->hasAnyRole(['Teacher', 'Incharge'])) {
            return back()->withErrors(['teacher_id' => 'Selected user must have Teacher or Incharge role.']);
        }

        DB::transaction(function () use ($request, $assignment) {
            $assignment->update([
                'teacher_id' => $request->teacher_id,
                'class_id' => $request->class_id,
                'assignment_type' => $request->assignment_type,
                'subject_id' => $request->assignment_type === 'subject' ? $request->subject_id : null,
                'course_id' => $request->assignment_type === 'course' ? $request->course_id : null,
                'designation' => $request->designation,
                'is_active' => $request->boolean('is_active', true),
            ]);
        });

        return redirect()->route('admin.teacher-assignments.index')
            ->with('success', 'Teacher assignment updated successfully!');
    }

    public function destroy($id)
    {
        $assignment = TeacherAssignment::findOrFail($id);
        $assignment->delete();

        return redirect()->route('admin.teacher-assignments.index')
            ->with('success', 'Teacher assignment deleted successfully!');
    }

    public function toggleStatus($id)
    {
        $assignment = TeacherAssignment::findOrFail($id);
        $assignment->is_active = !$assignment->is_active;
        $assignment->save();

        return back()->with('success', 'Assignment status updated!');
    }
}