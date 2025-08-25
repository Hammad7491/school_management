@extends('students.layouts.app')

@section('content')
<div class="container" style="max-width:1100px">
    <h2 class="mb-3">Exam Results</h2>

    {{-- Student header --}}
    <div class="card mb-3">
        <div class="card-body d-flex justify-content-between flex-wrap">
            <div>
                <h4 class="mb-1">{{ $student->name }}</h4>
                <div class="text-muted small">Reg #: {{ $student->reg_no }}</div>
            </div>
            <div class="d-flex gap-3">
                <div>
                    <div class="small text-muted">Class</div>
                    <strong>{{ $student->schoolClass?->name ?? '—' }}</strong>
                </div>
                <div>
                    <div class="small text-muted">Course</div>
                    <strong>{{ $student->course?->name ?? '—' }}</strong>
                </div>
            </div>
        </div>
    </div>

    {{-- Term filter --}}
    <form method="GET" class="card mb-3">
        <div class="card-body d-flex gap-2 align-items-end">
            <div style="min-width:260px">
                <label class="form-label">Term</label>
                <select name="term_id" class="form-control">
                    <option value="">— Select Term —</option>
                    @foreach($terms as $t)
                        <option value="{{ $t->id }}" {{ (string)$termId === (string)$t->id ? 'selected' : '' }}>
                            {{ $t->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button class="btn btn-primary" type="submit">Show</button>
        </div>
    </form>

    @if(!$termId)
        <div class="alert alert-info">Please select a term to view your results.</div>
    @else

        {{-- Class scope results --}}
        @if($student->class_id)
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between">
                <strong>Class Results</strong>
                <div class="small text-muted">
                    Total: {{ $totals['class']['total'] }} |
                    Obtained: {{ $totals['class']['obtained'] }} |
                    @php
                        $p = $totals['class']['total'] ? round(($totals['class']['obtained']/$totals['class']['total'])*100,2) : null;
                    @endphp
                    Overall %: {{ $p !== null ? $p.'%' : '—' }}
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th class="text-end">Total</th>
                            <th class="text-end">Obtained</th>
                            <th class="text-end">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classResults as $r)
                            @php
                                $perc = $r->total_marks ? round(($r->obtained_marks/$r->total_marks)*100,2) : null;
                                // Make subject label safe for both relation and legacy string
                                $subjectLabel = $r->subject->name
                                    ?? ($r->subject_name ?? (is_string($r->subject) ? $r->subject : '—'));
                            @endphp
                            <tr>
                                <td>{{ $subjectLabel }}</td>
                                <td class="text-end">{{ $r->total_marks }}</td>
                                <td class="text-end">{{ $r->obtained_marks }}</td>
                                <td class="text-end">{{ $perc !== null ? $perc.'%' : '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">No class results for this term.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($classAttendance)
                <div class="card-footer small">
                    Attendance: {{ $classAttendance->present_days }}/{{ $classAttendance->total_days }}
                    ({{ $classAttendance->percentage ?? '—' }}%)
                </div>
            @endif
        </div>
        @endif

        {{-- Course scope results --}}
        @if($student->course_id)
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between">
                <strong>Course Results</strong>
                <div class="small text-muted">
                    Total: {{ $totals['course']['total'] }} |
                    Obtained: {{ $totals['course']['obtained'] }} |
                    @php
                        $p2 = $totals['course']['total'] ? round(($totals['course']['obtained']/$totals['course']['total'])*100,2) : null;
                    @endphp
                    Overall %: {{ $p2 !== null ? $p2.'%' : '—' }}
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th class="text-end">Total</th>
                            <th class="text-end">Obtained</th>
                            <th class="text-end">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courseResults as $r)
                            @php
                                $perc = $r->total_marks ? round(($r->obtained_marks/$r->total_marks)*100,2) : null;
                                $subjectLabel = $r->subject->name
                                    ?? ($r->subject_name ?? (is_string($r->subject) ? $r->subject : '—'));
                            @endphp
                            <tr>
                                <td>{{ $subjectLabel }}</td>
                                <td class="text-end">{{ $r->total_marks }}</td>
                                <td class="text-end">{{ $r->obtained_marks }}</td>
                                <td class="text-end">{{ $perc !== null ? $perc.'%' : '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">No course results for this term.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($courseAttendance)
                <div class="card-footer small">
                    Attendance: {{ $courseAttendance->present_days }}/{{ $courseAttendance->total_days }}
                    ({{ $courseAttendance->percentage ?? '—' }}%)
                </div>
            @endif
        </div>
        @endif

        @if(!$student->class_id && !$student->course_id)
            <div class="alert alert-warning">You are not enrolled in a class or course.</div>
        @endif

    @endif
</div>
@endsection
