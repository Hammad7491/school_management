@extends('students.layouts.app')

@section('content')
<div class="container" style="max-width:1100px">

    <h2 class="mb-4">Student Dashboard</h2>

    <div class="row g-3">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between flex-wrap">
                    <div>
                        <h4 class="mb-1">{{ $student->name }}</h4>
                        <div class="text-muted small">Reg #: {{ $student->reg_no }}</div>
                    </div>

                    <div class="d-flex gap-3 flex-wrap">
                        <div class="p-3 border rounded-3">
                            <div class="text-muted small mb-1">Class</div>
                            <div class="fw-semibold">
                                {{ $student->schoolClass?->name ?? '— Not enrolled —' }}
                            </div>
                        </div>

                        <div class="p-3 border rounded-3">
                            <div class="text-muted small mb-1">Course</div>
                            <div class="fw-semibold">
                                {{ $student->course?->name ?? '— Not enrolled —' }}
                            </div>
                        </div>

                        <div class="p-3 border rounded-3">
                            <div class="text-muted small mb-1">Status</div>
                            <div class="fw-semibold">
                                @if ($student->status === 1)
                                    <span class="badge text-bg-success">Approved</span>
                                @elseif ($student->status === 0)
                                    <span class="badge text-bg-danger">Rejected</span>
                                @else
                                    <span class="badge text-bg-secondary">Pending</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex flex-wrap gap-2">
                    <a class="btn btn-outline-primary btn-sm" href="{{ route('student.homeworks') }}">View Homework</a>
                    <a class="btn btn-outline-primary btn-sm" href="{{ route('student.exams') }}">View Exams</a>
                    <a class="btn btn-outline-primary btn-sm" href="{{ route('student.monthlyreports') }}">Monthly Reports</a>
                    <a class="btn btn-primary btn-sm" href="{{ route('student.results') }}">Check Results</a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
