<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\VacationRequest;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VacationRequestController extends Controller
{
    protected function currentStudent(): Student
    {
        // adjust if your user->student relation is different
        return Student::where('user_id', Auth::id())->firstOrFail();
    }

    public function index()
    {
        $student  = $this->currentStudent();
        $requests = VacationRequest::where('student_id', $student->id)->latest()->paginate(12);

        return view('students.vacationrequests.index', compact('student','requests'));
    }

    public function create()
    {
        $student = $this->currentStudent();
        return view('students.vacationrequests.create', compact('student'));
    }

    public function store(Request $request)
    {
        $student = $this->currentStudent();

        $data = $request->validate([
            'reason'     => 'required|string|max:2000',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        VacationRequest::create([
            'student_id'   => $student->id,
            'class_id'     => $student->class_id,
            'reg_no'       => $student->reg_no,
            'student_name' => $student->name,
            'status'       => 'pending',
            'reason'       => $data['reason'],
            'start_date'   => $data['start_date'] ?? null,
            'end_date'     => $data['end_date'] ?? null,
        ]);

        return redirect()->route('student.vacationrequests.index')
            ->with('success','Request submitted.');
    }
}
