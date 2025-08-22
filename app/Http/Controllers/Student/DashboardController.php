<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Homework;
use App\Models\Exam;
use App\Models\MonthlyReport;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Student overview (name, reg#, class, course, status).
     */
    public function index()
    {
        $student = Student::with(['schoolClass', 'course'])
            ->where('student_id', Auth::id())
            ->firstOrFail();

        return view('students.dashboard', compact('student'));
    }

    /**
     * Student homework list filtered by their class_id and/or course_id.
     * Shows items if:
     *  - class_id equals student's class_id, OR
     *  - course_id equals student's course_id.
     */
    public function homeworks()
    {
        $student = Student::where('student_id', Auth::id())->firstOrFail();

        $query = Homework::with(['schoolClass', 'course', 'user'])
            ->orderByDesc('day')
            ->orderByDesc('id');

        // Apply filters only if the student has enrollments
        $query->where(function ($q) use ($student) {
            $hasAny = false;

            if (!is_null($student->class_id)) {
                $q->orWhere('class_id', $student->class_id);
                $hasAny = true;
            }

            if (!is_null($student->course_id)) {
                $q->orWhere('course_id', $student->course_id);
                $hasAny = true;
            }

            // If student has neither class nor course, force empty result
            if (!$hasAny) {
                $q->whereRaw('1=0');
            }
        });

        $homeworks = $query->paginate(12);

        return view('students.homeworks.index', compact('student', 'homeworks'));
    }

    /**
     * Student exam list filtered by their class_id and/or course_id.
     */
    public function exams()
    {
        $student = Student::where('student_id', Auth::id())->firstOrFail();

        $query = Exam::with(['schoolClass', 'course'])
            ->orderByDesc('id');

        $query->where(function ($q) use ($student) {
            $hasAny = false;

            if (!is_null($student->class_id)) {
                $q->orWhere('class_id', $student->class_id);
                $hasAny = true;
            }

            if (!is_null($student->course_id)) {
                $q->orWhere('course_id', $student->course_id);
                $hasAny = true;
            }

            if (!$hasAny) {
                $q->whereRaw('1=0');
            }
        });

        $exams = $query->paginate(12);

        return view('students.exams.index', compact('student', 'exams'));
    }

    /**
     * Student monthly reports filtered STRICTLY by Reg # (roll number).
     * Only reports with reg_no == student's reg_no are visible.
     */
    public function monthlyReports()
    {
        $student = Student::with(['schoolClass', 'course'])
            ->where('student_id', Auth::id())
            ->firstOrFail();

        $reports = MonthlyReport::with(['schoolClass', 'course', 'creator'])
            ->where('reg_no', $student->reg_no)
            ->latest('report_date')
            ->latest('id')
            ->paginate(12);

        return view('students.monthlyreports.index', compact('student', 'reports'));
    }
}
