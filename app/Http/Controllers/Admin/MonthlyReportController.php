<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MonthlyReport;
use App\Models\SchoolClass;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MonthlyReportController extends Controller
{
    public function index()
    {
        $reports = MonthlyReport::with(['schoolClass','course','creator'])
            ->latest('report_date')->latest('id')
            ->paginate(12);

        return view('admin.monthlyreports.index', compact('reports'));
    }

    public function create()
    {
        $classes = SchoolClass::orderByRaw(
            "FIELD(name,'Play Group','Nursery','Prep','1','2','3','4','5','6','7','8','9','10')"
        )->get();
        $courses = Course::orderBy('name')->get();

        return view('admin.monthlyreports.create', compact('classes','courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'report_date'  => 'required|date',
            'reg_no'       => 'required|string|max:20',
            'student_name' => 'required|string|max:150',
            'father_name'  => 'nullable|string|max:150',
            'remarks'      => 'nullable|string',
            'add_class'    => 'nullable|boolean',
            'class_id'     => 'nullable|exists:classes,id',
            'add_course'   => 'nullable|boolean',
            'course_id'    => 'nullable|exists:courses,id',
            'file'         => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:8192',
        ]);

        if ($request->boolean('add_class')) {
            $request->validate(['class_id' => 'required|exists:classes,id']);
        }
        if ($request->boolean('add_course')) {
            $request->validate(['course_id' => 'required|exists:courses,id']);
        }

        $stu = Student::where('reg_no', $request->reg_no)->first();

        $path = null; $orig = null;
        if ($request->hasFile('file')) {
            $orig = $request->file('file')->getClientOriginalName();
            $path = $request->file('file')->store('monthlyreports', 'public');
        }

        MonthlyReport::create([
            'user_id'      => Auth::id(),
            'class_id'     => $request->boolean('add_class')  ? $request->class_id  : null,
            'course_id'    => $request->boolean('add_course') ? $request->course_id : null,
            'reg_no'       => $request->reg_no,
            'student_id'   => $stu?->id,
            'report_date'  => $request->report_date,
            'student_name' => $request->student_name ?: ($stu->name ?? ''),
            'father_name'  => $request->father_name ?: ($stu->father_name ?? null),
            'remarks'      => $request->remarks,
            'file_path'    => $path,
            'file_name'    => $orig,
        ]);

        return redirect()->route('monthlyreports.index')->with('success', 'Monthly report added.');
    }

    public function edit($id)
    {
        $report  = MonthlyReport::findOrFail($id);
        $classes = SchoolClass::orderByRaw(
            "FIELD(name,'Play Group','Nursery','Prep','1','2','3','4','5','6','7','8','9','10')"
        )->get();
        $courses = Course::orderBy('name')->get();

        return view('admin.monthlyreports.create', compact('report','classes','courses'));
    }

    public function update(Request $request, $id)
    {
        $report = MonthlyReport::findOrFail($id);

        $request->validate([
            'report_date'  => 'required|date',
            'reg_no'       => 'required|string|max:20',
            'student_name' => 'required|string|max:150',
            'father_name'  => 'nullable|string|max:150',
            'remarks'      => 'nullable|string',
            'add_class'    => 'nullable|boolean',
            'class_id'     => 'nullable|exists:classes,id',
            'add_course'   => 'nullable|boolean',
            'course_id'    => 'nullable|exists:courses,id',
            'file'         => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:8192',
        ]);

        if ($request->boolean('add_class')) {
            $request->validate(['class_id' => 'required|exists:classes,id']);
        }
        if ($request->boolean('add_course')) {
            $request->validate(['course_id' => 'required|exists:courses,id']);
        }

        $stu = Student::where('reg_no', $request->reg_no)->first();

        if ($request->hasFile('file')) {
            if ($report->file_path && Storage::disk('public')->exists($report->file_path)) {
                Storage::disk('public')->delete($report->file_path);
            }
            $report->file_name = $request->file('file')->getClientOriginalName();
            $report->file_path = $request->file('file')->store('monthlyreports', 'public');
        }

        $report->class_id     = $request->boolean('add_class')  ? $request->class_id  : null;
        $report->course_id    = $request->boolean('add_course') ? $request->course_id : null;
        $report->reg_no       = $request->reg_no;
        $report->student_id   = $stu?->id;
        $report->report_date  = $request->report_date;
        $report->student_name = $request->student_name ?: ($stu->name ?? '');
        $report->father_name  = $request->father_name ?: ($stu->father_name ?? null);
        $report->remarks      = $request->remarks;
        $report->save();

        return redirect()->route('monthlyreports.index')->with('success', 'Monthly report updated.');
    }

    public function destroy($id)
    {
        $report = MonthlyReport::findOrFail($id);
        if ($report->file_path && Storage::disk('public')->exists($report->file_path)) {
            Storage::disk('public')->delete($report->file_path);
        }
        $report->delete();

        return redirect()->route('monthlyreports.index')->with('success', 'Monthly report deleted.');
    }

    public function download($id)
    {
        $report = MonthlyReport::findOrFail($id);
        if (!$report->file_path || !Storage::disk('public')->exists($report->file_path)) {
            return back()->with('error', 'File not found.');
        }
        $name = $report->file_name ?: basename($report->file_path);
        return Storage::disk('public')->download($report->file_path, $name);
    }
}
