<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Homework;
use App\Models\SchoolClass;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HomeworkController extends Controller
{
    public function index()
    {
        $homeworks = Homework::with(['schoolClass','course','user'])
            ->orderByDesc('day')
            ->latest('id')
            ->paginate(12);

        return view('admin.homeworks.index', compact('homeworks'));
    }

    public function create()
    {
        $classes = SchoolClass::orderByRaw(
            "FIELD(name,'Play Group','Nursery','Prep','1','2','3','4','5','6','7','8','9','10')"
        )->get();

        $courses = Course::orderBy('name')->get();

        return view('admin.homeworks.create', compact('classes','courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'day'        => 'required|date',
            'comment'    => 'nullable|string',
            'add_class'  => 'nullable|boolean',
            'add_course' => 'nullable|boolean',
            'class_id'   => 'nullable|exists:classes,id',
            'course_id'  => 'nullable|exists:courses,id',
            'file'       => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:5120', // 5MB
        ]);

        // At least one of class/course must be selected
        if (!$request->boolean('add_class') && !$request->boolean('add_course')) {
            return back()
                ->withErrors(['add_class' => 'Select at least Class or Course.'])
                ->withInput();
        }
        if ($request->boolean('add_class')) {
            $request->validate(['class_id' => 'required|exists:classes,id']);
        }
        if ($request->boolean('add_course')) {
            $request->validate(['course_id' => 'required|exists:courses,id']);
        }

        $path = null; $original = null;
        if ($request->hasFile('file')) {
            $original = $request->file('file')->getClientOriginalName();
            $path     = $request->file('file')->store('homeworks', 'public');
        }

        Homework::create([
            'user_id'   => Auth::id(),
            'class_id'  => $request->boolean('add_class')  ? $request->class_id  : null,
            'course_id' => $request->boolean('add_course') ? $request->course_id : null,
            'file_path' => $path,
            'file_name' => $original,
            'comment'   => $request->comment,
            'day'       => $request->day,
        ]);

        return redirect()->route('homeworks.index')->with('success', 'Homework created.');
    }

    public function edit($id)
    {
        $homework = Homework::findOrFail($id);

        $classes = SchoolClass::orderByRaw(
            "FIELD(name,'Play Group','Nursery','Prep','1','2','3','4','5','6','7','8','9','10')"
        )->get();

        $courses = Course::orderBy('name')->get();

        return view('admin.homeworks.create', compact('homework','classes','courses'));
    }

    public function update(Request $request, $id)
    {
        $hw = Homework::findOrFail($id);

        $request->validate([
            'day'        => 'required|date',
            'comment'    => 'nullable|string',
            'add_class'  => 'nullable|boolean',
            'add_course' => 'nullable|boolean',
            'class_id'   => 'nullable|exists:classes,id',
            'course_id'  => 'nullable|exists:courses,id',
            'file'       => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
        ]);

        if (!$request->boolean('add_class') && !$request->boolean('add_course')) {
            return back()
                ->withErrors(['add_class' => 'Select at least Class or Course.'])
                ->withInput();
        }
        if ($request->boolean('add_class')) {
            $request->validate(['class_id' => 'required|exists:classes,id']);
        }
        if ($request->boolean('add_course')) {
            $request->validate(['course_id' => 'required|exists:courses,id']);
        }

        if ($request->hasFile('file')) {
            if ($hw->file_path && Storage::disk('public')->exists($hw->file_path)) {
                Storage::disk('public')->delete($hw->file_path);
            }
            $hw->file_name = $request->file('file')->getClientOriginalName();
            $hw->file_path = $request->file('file')->store('homeworks', 'public');
        }

        $hw->class_id  = $request->boolean('add_class')  ? $request->class_id  : null;
        $hw->course_id = $request->boolean('add_course') ? $request->course_id : null;
        $hw->comment   = $request->comment;
        $hw->day       = $request->day;
        $hw->save();

        return redirect()->route('homeworks.index')->with('success', 'Homework updated.');
    }

    public function destroy($id)
    {
        $hw = Homework::findOrFail($id);
        if ($hw->file_path && Storage::disk('public')->exists($hw->file_path)) {
            Storage::disk('public')->delete($hw->file_path);
        }
        $hw->delete();

        return redirect()->route('homeworks.index')->with('success', 'Homework deleted.');
    }

    public function download($id)
    {
        $hw = Homework::findOrFail($id);
        if (!$hw->file_path || !Storage::disk('public')->exists($hw->file_path)) {
            return back()->with('success', 'File not found.');
        }
        $name = $hw->file_name ?: basename($hw->file_path);
        return Storage::disk('public')->download($hw->file_path, $name);
    }
}
