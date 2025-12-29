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

        return view('admin.results.index', compact('terms', 'classes', 'courses'));
    }

    /**
     * Handle CSV upload (ONE ROW = ONE STUDENT, MULTIPLE SUBJECT COLUMNS).
     *
     * Expected CSV headers (example):
     *   reg_no,name,class,english,math,physics,chemistry,urdu,islamiyat,attendance_total,attendance_present,remarks
     *
     * Reserved columns (special handling):
     *   - reg_no              (required)
     *   - name                (optional, ignored in DB)
     *   - class               (optional, ignored in DB)   <-- IMPORTANT FIX
     *   - course              (optional, ignored in DB)   <-- IMPORTANT FIX
     *   - exam_date           (optional, same for all subjects in that row)
     *   - attendance_total    (optional)
     *   - attendance_present  (optional)
     *   - remarks             (optional)
     *
     * All other columns are treated as SUBJECT NAMES and their values
     * are treated as obtained marks out of a default total (e.g. 100).
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

        // Backward-compatibility: if the table still has a text 'subject' column
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

        // Original & normalized headers
        $originalHeader = array_map('trim', $header);
        $lowerHeader    = array_map(fn($h) => strtolower(trim($h)), $header);

        // Build map: lower_header => index
        $map = [];
        foreach ($lowerHeader as $i => $h) {
            if ($h !== '') $map[$h] = $i;
        }

        // Required: reg_no
        if (!array_key_exists('reg_no', $map)) {
            fclose($handle);
            return back()->withErrors(['csv' => "Missing required column: reg_no"]);
        }

        // Optional columns
        $examDateIndex          = $map['exam_date']           ?? null;
        $remarksIndex           = $map['remarks']             ?? null;
        $attendanceTotalIndex   = $map['attendance_total']    ?? null;
        $attendancePresentIndex = $map['attendance_present']  ?? null;
        $nameIndex              = $map['name']                ?? null;

        /**
         * IMPORTANT FIX #1:
         * Add 'class' and 'course' here so your CSV column "class" is NOT treated as a subject.
         */
        $reserved = [
            'reg_no',
            'name',
            'class',
            'course',
            'exam_date',
            'attendance_total',
            'attendance_present',
            'remarks',
        ];

        // Detect subject columns: any header not in reserved list
        $subjectColumns = [];
        foreach ($lowerHeader as $i => $lh) {
            if (!in_array($lh, $reserved, true) && $originalHeader[$i] !== '') {
                $subjectColumns[] = [
                    'index' => $i,
                    'name'  => trim($originalHeader[$i]), // original header as subject name
                ];
            }
        }

        if (empty($subjectColumns)) {
            fclose($handle);
            return back()->withErrors(['csv' => 'No subject columns detected.']);
        }

        $defaultTotalMarks = 100;

        $inserted  = 0;
        $updated   = 0;
        $skipped   = 0;
        $rowErrors = [];

        $uploaderId = Auth::id(); // reuse

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle)) !== false) {

                // ---- Core per-student data ----
                $regNo = trim($row[$map['reg_no']] ?? '');
                if ($regNo === '') {
                    $skipped++;
                    $rowErrors[] = "Missing reg_no in a row.";
                    continue;
                }

                // Optional name (ignored in DB)
                $name = $nameIndex !== null ? trim($row[$nameIndex] ?? '') : null;

                // Find student
                $student = Student::where('reg_no', $regNo)->first();
                if (!$student) {
                    $skipped++;
                    $rowErrors[] = "Student not found: {$regNo}";
                    continue;
                }

                // Scope membership checks
                if ($classId && (int) $student->class_id !== $classId) {
                    $skipped++;
                    $rowErrors[] = "Student {$regNo} not in selected class.";
                    continue;
                }
                if ($courseId && (int) $student->course_id !== $courseId) {
                    $skipped++;
                    $rowErrors[] = "Student {$regNo} not in selected course.";
                    continue;
                }

                // Optional exam_date (same for all subjects in student row)
                $examDate = null;
                if ($examDateIndex !== null) {
                    $raw = trim($row[$examDateIndex] ?? '');
                    if ($raw !== '') {
                        $ts = strtotime($raw);
                        if ($ts !== false) $examDate = date('Y-m-d', $ts);
                    }
                }

                // Optional remarks
                $remarks = $remarksIndex !== null ? trim($row[$remarksIndex] ?? '') : null;

                // ---- Handle all subjects in this row ----
                $hasAnySubjectForStudent = false;

                foreach ($subjectColumns as $subCol) {
                    $subjectName  = $subCol['name'];
                    $subjectIndex = $subCol['index'];

                    $rawMarks = trim($row[$subjectIndex] ?? '');
                    if ($rawMarks === '') continue;

                    if (!is_numeric($rawMarks)) {
                        $skipped++;
                        $rowErrors[] = "Invalid marks for subject '{$subjectName}' (reg_no={$regNo}).";
                        continue;
                    }

                    $omarks = (int) $rawMarks;
                    $tmarks = $defaultTotalMarks;

                    if ($tmarks <= 0 || $omarks < 0 || $omarks > $tmarks) {
                        $skipped++;
                        $rowErrors[] = "Out-of-range marks for subject '{$subjectName}' (reg_no={$regNo}).";
                        continue;
                    }

                    /**
                     * IMPORTANT FIX #2:
                     * When creating a subject, pass user_id (because your subjects table requires it).
                     * This directly fixes: "Field 'user_id' doesn't have a default value"
                     */
                    $sub = Subject::whereRaw('LOWER(name) = ?', [mb_strtolower($subjectName)])->first();
                    if (!$sub) {
                        $sub = Subject::create([
                            'name'    => $subjectName,
                            'user_id' => $uploaderId,
                        ]);
                    }

                    $percentage = round(($omarks / max(1, $tmarks)) * 100, 2);

                    // UPSERT per (student, term, subject, scope)
                    $attrs = [
                        'student_id' => $student->id,
                        'term_id'    => $termId,
                        'subject_id' => $sub->id,
                        'class_id'   => $classId,
                        'course_id'  => $courseId,
                    ];

                    $values = [
                        'user_id'        => $uploaderId,
                        'reg_no'         => $student->reg_no,
                        'exam_date'      => $examDate,
                        'total_marks'    => $tmarks,
                        'obtained_marks' => $omarks,
                        'percentage'     => $percentage,
                        'grade'          => null,
                        'remarks'        => $remarks,
                    ];

                    if ($hasSubjectTextCol) {
                        $values['subject'] = $subjectName;
                    }

                    $existing = ExamResult::where($attrs)->first();
                    if ($existing) {
                        $existing->update($values);
                        $updated++;
                    } else {
                        ExamResult::create($attrs + $values);
                        $inserted++;
                    }

                    $hasAnySubjectForStudent = true;
                }

                if (!$hasAnySubjectForStudent) {
                    $rowErrors[] = "No valid subject marks found for reg_no={$regNo}.";
                    $skipped++;
                }

                // ---- Attendance (once per student/term/scope) ----
                $attTotal = $attendanceTotalIndex !== null ? trim($row[$attendanceTotalIndex] ?? '') : null;
                $attPres  = $attendancePresentIndex !== null ? trim($row[$attendancePresentIndex] ?? '') : null;

                $attTotalInt = ($attTotal !== null && $attTotal !== '' && is_numeric($attTotal)) ? (int) $attTotal : null;
                $attPresInt  = ($attPres  !== null && $attPres  !== '' && is_numeric($attPres))  ? (int) $attPres  : null;

                if ($attTotalInt !== null || $attPresInt !== null) {
                    $att = Attendance::firstOrNew([
                        'student_id' => $student->id,
                        'term_id'    => $termId,
                        'class_id'   => $classId,
                        'course_id'  => $courseId,
                    ]);

                    $att->total_days   = $attTotalInt !== null ? $attTotalInt : ($att->total_days ?? 0);
                    $att->present_days = $attPresInt  !== null ? $attPresInt  : ($att->present_days ?? 0);
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
            return back()->withErrors(['csv' => 'Error: ' . $e->getMessage()]);
        }

        fclose($handle);

        return back()
            ->with('success', "Upload complete. Inserted: {$inserted}, Updated: {$updated}, Skipped (subjects/rows): {$skipped}")
            ->with('errors_list', $rowErrors);
    }
}
