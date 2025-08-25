<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ExamResult;
use App\Models\Attendance;
use App\Models\ExamTerm;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResultController extends Controller
{
    protected function me()
    {
        $student = Student::with(['schoolClass','course'])
            ->where('student_id', Auth::id())
            ->first();

        abort_if(!$student, 403, 'Student profile not found.');
        return $student;
    }

    public function index(Request $request)
    {
        $student = $this->me();
        $terms = ExamTerm::orderBy('name')->get();

        $termId = $request->integer('term_id') ?: null;
        $classResults = collect();
        $courseResults = collect();
        $classAttendance = null;
        $courseAttendance = null;
        $totals = [
            'class'  => ['total' => 0, 'obtained' => 0],
            'course' => ['total' => 0, 'obtained' => 0],
        ];

        if ($termId) {
            // Class-scope results
            if ($student->class_id) {
                $classResults = ExamResult::with('subject')
                    ->where('student_id', $student->id)
                    ->where('term_id', $termId)
                    ->where('class_id', $student->class_id)
                    ->orderBy('subject_id')
                    ->get();

                $totals['class']['total']    = $classResults->sum('total_marks');
                $totals['class']['obtained'] = $classResults->sum('obtained_marks');

                $classAttendance = Attendance::where([
                    'student_id' => $student->id,
                    'term_id'    => $termId,
                    'class_id'   => $student->class_id,
                ])->first();
            }

            // Course-scope results
            if ($student->course_id) {
                $courseResults = ExamResult::with('subject')
                    ->where('student_id', $student->id)
                    ->where('term_id', $termId)
                    ->where('course_id', $student->course_id)
                    ->orderBy('subject_id')
                    ->get();

                $totals['course']['total']    = $courseResults->sum('total_marks');
                $totals['course']['obtained'] = $courseResults->sum('obtained_marks');

                $courseAttendance = Attendance::where([
                    'student_id' => $student->id,
                    'term_id'    => $termId,
                    'course_id'  => $student->course_id,
                ])->first();
            }
        }

        return view('students.results.index', compact(
            'student','terms','termId',
            'classResults','courseResults',
            'classAttendance','courseAttendance','totals'
        ));
    }
}
