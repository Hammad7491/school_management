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
     * Get the logged-in user's Student profile or abort with 403
     */
    protected function currentStudent()
    {
        $student = Student::with(['schoolClass','course'])
            ->where('student_id', Auth::id())
            ->first();

        abort_if(!$student, 403, 'Student profile not found for this account.');
        return $student;
    }

    /**
     * Student dashboard: basic info + quick links
     */
    public function index()
    {
        $student = $this->currentStudent();

        return view('students.dashboard', compact('student'));
    }

    /**
     * Student view: Homeworks filtered by their class/course
     * Shows items where class_id == student's class OR course_id == student's course
     */
    public function homeworks()
    {
        $student = $this->currentStudent();
        $classId  = $student->class_id;
        $courseId = $student->course_id;

        $homeworks = Homework::with(['schoolClass','course','user'])
            ->where(function ($q) use ($classId, $courseId) {
                // If student has class, include class-targeted homeworks
                if (!is_null($classId)) {
                    $q->orWhere('class_id', $classId);
                }
                // If student has course, include course-targeted homeworks
                if (!is_null($courseId)) {
                    $q->orWhere('course_id', $courseId);
                }
            })
            ->latest('day')->latest('id')
            ->paginate(12);

        return view('students.homeworks.index', compact('student','homeworks'));
    }

    /**
     * Student view: Exams filtered by their class/course
     */
    public function exams()
    {
        $student = $this->currentStudent();
        $classId  = $student->class_id;
        $courseId = $student->course_id;

        $exams = Exam::with(['schoolClass','course'])
            ->where(function ($q) use ($classId, $courseId) {
                if (!is_null($classId)) {
                    $q->orWhere('class_id', $classId);
                }
                if (!is_null($courseId)) {
                    $q->orWhere('course_id', $courseId);
                }
            })
            ->latest('id')
            ->paginate(12);

        return view('students.exams.index', compact('student','exams'));
    }

    /**
     * Student view: Monthly Reports (filtered strictly by student's Reg #)
     * Student only sees reports where reg_no == their reg_no (roll no).
     */
    public function monthlyReports()
    {
        $student = $this->currentStudent();

        $reports = MonthlyReport::with(['schoolClass','course'])
            ->where('reg_no', $student->reg_no)
            ->latest('report_date')->latest('id')
            ->paginate(12);

        return view('students.monthlyreports.index', compact('student','reports'));
    }
}
