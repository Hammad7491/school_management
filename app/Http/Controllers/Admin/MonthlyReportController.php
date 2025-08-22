<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MonthlyReport;
use App\Models\SchoolClass;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonthlyReportController extends Controller
{
    public function index()
    {
        $reports = MonthlyReport::with(['schoolClass','course','creator'])
            ->latest('report_date')
            ->latest('id')
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
        ]);

        if ($request->boolean('add_class')) {
            $request->validate(['class_id' => 'required|exists:classes,id']);
        }
        if ($request->boolean('add_course')) {
            $request->validate(['course_id' => 'required|exists:courses,id']);
        }

        // Try to link to a student by reg_no
        $stu = Student::where('reg_no', $request->reg_no)->first();

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
        ]);

        if ($request->boolean('add_class')) {
            $request->validate(['class_id' => 'required|exists:classes,id']);
        }
        if ($request->boolean('add_course')) {
            $request->validate(['course_id' => 'required|exists:courses,id']);
        }

        $stu = Student::where('reg_no', $request->reg_no)->first();

        $report->update([
            'class_id'     => $request->boolean('add_class')  ? $request->class_id  : null,
            'course_id'    => $request->boolean('add_course') ? $request->course_id : null,
            'reg_no'       => $request->reg_no,
            'student_id'   => $stu?->id,
            'report_date'  => $request->report_date,
            'student_name' => $request->student_name ?: ($stu->name ?? ''),
            'father_name'  => $request->father_name ?: ($stu->father_name ?? null),
            'remarks'      => $request->remarks,
        ]);

        return redirect()->route('monthlyreports.index')->with('success', 'Monthly report updated.');
    }

    public function destroy($id)
    {
        $report = MonthlyReport::findOrFail($id);
        $report->delete();

        return redirect()->route('monthlyreports.index')->with('success', 'Monthly report deleted.');
    }
}
