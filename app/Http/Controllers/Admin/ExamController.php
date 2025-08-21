<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\SchoolClass;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::with(['schoolClass','course'])
            ->latest()
            ->paginate(12);

        return view('admin.exams.index', compact('exams'));
    }

    public function create()
    {
        $classes = SchoolClass::orderByRaw(
            "FIELD(name,'Play Group','Nursery','Prep','1','2','3','4','5','6','7','8','9','10')"
        )->get();
        $courses = Course::orderBy('name')->get();

        return view('admin.exams.create', compact('classes','courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'comment'     => 'nullable|string',
            'add_class'   => 'nullable|boolean',
            'class_id'    => 'nullable|exists:classes,id',
            'add_course'  => 'nullable|boolean',
            'course_id'   => 'nullable|exists:courses,id',
            'file'        => 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:8192',
        ]);

        if ($request->boolean('add_class')) {
            $request->validate(['class_id' => 'required|exists:classes,id']);
        }
        if ($request->boolean('add_course')) {
            $request->validate(['course_id' => 'required|exists:courses,id']);
        }

        $path = null;
        $orig = null;
        if ($request->hasFile('file')) {
            $orig = $request->file('file')->getClientOriginalName();
            $path = $request->file('file')->store('exams', 'public');
        }

        Exam::create([
            'user_id'    => Auth::id(),
            'class_id'   => $request->boolean('add_class')  ? $request->class_id  : null,
            'course_id'  => $request->boolean('add_course') ? $request->course_id : null,
            'comment'    => $request->comment,
            'file_path'  => $path,
            'file_name'  => $orig,
        ]);

        return redirect()->route('exams.index')->with('success', 'Exam added!');
    }

    public function edit($id)
    {
        $exam    = Exam::findOrFail($id);
        $classes = SchoolClass::orderByRaw(
            "FIELD(name,'Play Group','Nursery','Prep','1','2','3','4','5','6','7','8','9','10')"
        )->get();
        $courses = Course::orderBy('name')->get();

        return view('admin.exams.create', compact('exam','classes','courses'));
    }

    public function update(Request $request, $id)
    {
        $exam = Exam::findOrFail($id);

        $request->validate([
            'comment'     => 'nullable|string',
            'add_class'   => 'nullable|boolean',
            'class_id'    => 'nullable|exists:classes,id',
            'add_course'  => 'nullable|boolean',
            'course_id'   => 'nullable|exists:courses,id',
            'file'        => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:8192',
        ]);

        if ($request->boolean('add_class')) {
            $request->validate(['class_id' => 'required|exists:classes,id']);
        }
        if ($request->boolean('add_course')) {
            $request->validate(['course_id' => 'required|exists:courses,id']);
        }

        if ($request->hasFile('file')) {
            if ($exam->file_path && Storage::disk('public')->exists($exam->file_path)) {
                Storage::disk('public')->delete($exam->file_path);
            }
            $exam->file_name = $request->file('file')->getClientOriginalName();
            $exam->file_path = $request->file('file')->store('exams', 'public');
        }

        $exam->class_id  = $request->boolean('add_class')  ? $request->class_id  : null;
        $exam->course_id = $request->boolean('add_course') ? $request->course_id : null;
        $exam->comment   = $request->comment;
        $exam->save();

        return redirect()->route('exams.index')->with('success', 'Exam updated!');
    }

    public function destroy($id)
    {
        $exam = Exam::findOrFail($id);

        if ($exam->file_path && Storage::disk('public')->exists($exam->file_path)) {
            Storage::disk('public')->delete($exam->file_path);
        }

        $exam->delete();

        return redirect()->route('exams.index')->with('success', 'Exam deleted!');
    }

    public function download($id)
    {
        $exam = Exam::findOrFail($id);
        if (!$exam->file_path || !Storage::disk('public')->exists($exam->file_path)) {
            return back()->with('success', 'File not found.');
        }
        $name = $exam->file_name ?: 'exam_file';
        return Storage::disk('public')->download($exam->file_path, $name);
    }
}
