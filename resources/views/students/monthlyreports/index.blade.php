@extends('students.layouts.app')

@section('content')
<div class="container" style="max-width:1100px">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="mb-0">Monthly Reports</h2>
        <div class="text-muted">
            {{ $student->name }} — Reg #: <strong>{{ $student->reg_no }}</strong>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:9rem;">Report Date</th>
                        <th>Student</th>
                        <th>Father</th>
                        <th>Class</th>
                        <th>Course</th>
                        <th>Remarks</th>
                        <th style="width:10rem;">File</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $r)
                        <tr>
                            <td class="fw-semibold">{{ optional($r->report_date)->format('Y-m-d') }}</td>
                            <td>{{ $r->student_name }}</td>
                            <td>{{ $r->father_name ?? '—' }}</td>
                            <td>{{ $r->schoolClass?->name ?? '—' }}</td>
                            <td>{{ $r->course?->name ?? '—' }}</td>
                            <td class="text-truncate" style="max-width: 420px;">
                                {{ $r->remarks ?? '—' }}
                            </td>
                            <td>
                                @if($r->file_path)
                                    <a href="{{ route('monthlyreports.download', $r->id) }}">Download</a>
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">No monthly reports yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $reports->links() }}
        </div>
    </div>
</div>
@endsection
