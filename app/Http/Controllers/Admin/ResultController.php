<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamResult;
use App\Models\Attendance;
use App\Models\ExamTerm;
use App\Models\Subject;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ResultController extends Controller
{
    /**
     * Single-page results screen (upload form).
     */
    public function index()
    {
        $terms   = ExamTerm::orderBy('name')->get();
        $classes = SchoolClass::orderBy('name')->get();
        $courses = Course::orderBy('name')->get();

        return view('admin.results.index', compact('terms','classes','courses'));
    }

    /**
     * Handle CSV upload for class/course results.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'term_id'   => 'required|exists:exam_terms,id',
            'scope'     => 'required|in:class,course',
            'class_id'  => 'nullable|exists:classes,id',
            'course_id' => 'nullable|exists:courses,id',
            'csv'       => 'required|file|mimes:csv,txt|max:10240',
        ]);

        // Scope guardrails
        if ($request->scope === 'class' && !$request->class_id) {
            return back()->withErrors(['class_id' => 'Please select a class.'])->withInput();
        }
        if ($request->scope === 'course' && !$request->course_id) {
            return back()->withErrors(['course_id' => 'Please select a course.'])->withInput();
        }

        $termId   = (int) $request->term_id;
        $classId  = $request->scope === 'class'  ? (int) $request->class_id  : null;
        $courseId = $request->scope === 'course' ? (int) $request->course_id : null;

        // Detect if the table still has a NOT NULL `subject` text column.
        $hasSubjectTextCol = Schema::hasColumn('exam_results', 'subject');

        // Open CSV
        $handle = fopen($request->file('csv')->getRealPath(), 'r');
        if (!$handle) {
            return back()->withErrors(['csv' => 'Unable to read file.']);
        }

        // Header row
        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            return back()->withErrors(['csv' => 'Empty CSV.']);
        }

        // Normalize headers (lower-case, trimmed)
        $map = [];
        foreach ($header as $i => $h) {
            $map[strtolower(trim($h))] = $i;
        }

        // Required columns
        foreach (['reg_no','subject','total_marks','obtained_marks'] as $col) {
            if (!array_key_exists($col, $map)) {
                fclose($handle);
                return back()->withErrors(['csv' => "Missing required column: {$col}"]);
            }
        }

        // Optional columns
        $opt = [
            'exam_date'          => $map['exam_date']          ?? null,
            'remarks'            => $map['remarks']            ?? null,
            'attendance_total'   => $map['attendance_total']   ?? null,
            'attendance_present' => $map['attendance_present'] ?? null,
        ];

        $inserted = 0; $updated = 0; $skipped = 0; $rowErrors = [];

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle)) !== false) {
                $regNo   = trim($row[$map['reg_no']] ?? '');
                $subject = trim($row[$map['subject']] ?? '');
                $tmarks  = (int) ($row[$map['total_marks']]    ?? 0);
                $omarks  = (int) ($row[$map['obtained_marks']] ?? 0);

                // Basic validation per row
                if ($regNo === '' || $subject === '' || $tmarks <= 0 || $omarks < 0 || $omarks > $tmarks) {
                    $skipped++;
                    $rowErrors[] = "Invalid row (reg_no={$regNo}, subject={$subject})";
                    continue;
                }

                // Find student
                $student = Student::where('reg_no', $regNo)->first();
                if (!$student) {
                    $skipped++;
                    $rowErrors[] = "Student not found: {$regNo}";
                    continue;
                }

                // Scope membership checks
                if ($classId && (int)$student->class_id !== $classId) {
                    $skipped++;
                    $rowErrors[] = "Student {$regNo} not in selected class.";
                    continue;
                }
                if ($courseId && (int)$student->course_id !== $courseId) {
                    $skipped++;
                    $rowErrors[] = "Student {$regNo} not in selected course.";
                    continue;
                }

                // Resolve subject (case-insensitive) and auto-create if not present.
                $sub = Subject::whereRaw('LOWER(name) = ?', [mb_strtolower($subject)])->first();
                if (!$sub) {
                    $sub = Subject::create(['name' => $subject]);
                }

                // Optional fields
                $examDate = null;
                if ($opt['exam_date'] !== null) {
                    $raw = trim($row[$opt['exam_date']] ?? '');
                    if ($raw !== '') {
                        $ts = strtotime($raw);
                        if ($ts !== false) {
                            $examDate = date('Y-m-d', $ts);
                        }
                    }
                }
                $remarks = $opt['remarks'] !== null ? trim($row[$opt['remarks']] ?? '') : null;

                $percentage = round(($omarks / max(1, $tmarks)) * 100, 2);

                // UPSERT per (student, term, subject, scope)
                $attrs = [
                    'student_id' => $student->id,
                    'term_id'    => $termId,
                    'subject_id' => $sub->id,
                    'class_id'   => $classId,
                    'course_id'  => $courseId,
                ];

                // Common payload
                $values = [
                    'user_id'         => Auth::id(),        // who uploaded
                    'reg_no'          => $student->reg_no,  // snapshot of roll number
                    'exam_date'       => $examDate,
                    'total_marks'     => $tmarks,
                    'obtained_marks'  => $omarks,
                    'percentage'      => $percentage,
                    'grade'           => null,              // add grading policy later if needed
                    'remarks'         => $remarks,
                ];

                // If the table still has a NOT NULL `subject` text column, also fill it.
                if ($hasSubjectTextCol) {
                    $values['subject'] = $subject;
                }

                $existing = ExamResult::where($attrs)->first();
                if ($existing) {
                    $existing->update($values);
                    $updated++;
                } else {
                    ExamResult::create($attrs + $values);
                    $inserted++;
                }

                // Attendance (optional, once per student/term/scope)
                $attTotal = $opt['attendance_total']   !== null ? (int)($row[$opt['attendance_total']]   ?? 0) : null;
                $attPres  = $opt['attendance_present'] !== null ? (int)($row[$opt['attendance_present']] ?? 0) : null;

                if ($attTotal !== null || $attPres !== null) {
                    $att = Attendance::firstOrNew([
                        'student_id' => $student->id,
                        'term_id'    => $termId,
                        'class_id'   => $classId,
                        'course_id'  => $courseId,
                    ]);
                    // update numbers (keep previous if null in this row)
                    $att->total_days   = $attTotal ?? $att->total_days ?? 0;
                    $att->present_days = $attPres  ?? $att->present_days ?? 0;
                    $att->percentage   = $att->total_days
                        ? round(($att->present_days / max(1, $att->total_days)) * 100, 2)
                        : null;
                    $att->save();
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            fclose($handle);
            return back()->withErrors(['csv' => 'Error: '.$e->getMessage()]);
        }

        fclose($handle);

        return back()
            ->with('success', "Upload complete. Inserted: {$inserted}, Updated: {$updated}, Skipped: {$skipped}")
            ->with('errors_list', $rowErrors);
    }
}
