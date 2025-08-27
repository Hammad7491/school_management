<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\VacationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VacationRequestController extends Controller
{
    /**
     * Resolve the currently logged-in student's profile.
     * Your schema uses `students.student_id` to reference `users.id`.
     * If no profile is linked, return 403 instead of a generic 404.
     */
    protected function currentStudent(): Student
    {
        $student = Student::where('student_id', Auth::id())->first();

        if (! $student) {
            abort(403, 'No student profile is linked to your account. Please contact admin.');
        }

        return $student;
    }

    /**
     * List the logged-in student's vacation/leave requests.
     */
    public function index()
    {
        $student  = $this->currentStudent();

        $requests = VacationRequest::where('student_id', $student->id)
            ->latest()
            ->paginate(12);

        return view('students.vacationrequests.index', compact('student', 'requests'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        $student = $this->currentStudent();

        return view('students.vacationrequests.create', compact('student'));
    }

    /**
     * Store a new vacation/leave request.
     */
    public function store(Request $request)
    {
        $student = $this->currentStudent();

        $data = $request->validate([
            'reason'     => 'required|string|max:2000',
            'start_date' => 'required|date',
            'end_date'   =>  'required|date|after_or_equal:start_date',
        ]);

        VacationRequest::create([
            'student_id'   => $student->id,
            'class_id'     => $student->class_id,
            'reg_no'       => $student->reg_no,
            'student_name' => $student->name,
            'status'       => 'pending',           // default status in your flow
            'reason'       => $data['reason'],
            'start_date'   => $data['start_date'] ?? null,
            'end_date'     => $data['end_date']   ?? null,
        ]);

        // NOTE: use the new dashed route name
        return redirect()
            ->route('student.vacation-requests.index')
            ->with('success', 'Request submitted.');
    }
}
