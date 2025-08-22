@extends('layouts.app')

@section('content')
<div class="container" style="max-width:1100px">

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="mb-0">Monthly Reports</h2>
        <a href="{{ route('monthlyreports.create') }}" class="btn btn-primary">+ Add Monthly Report</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Reg #</th>
                        <th>Student</th>
                        <th>Father</th>
                        <th>Class</th>
                        <th>Course</th>
                        <th>Created By</th>
                        <th class="text-end" style="width:14rem;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $r)
                        <tr>
                            <td>{{ optional($r->report_date)->format('Y-m-d') }}</td>
                            <td class="fw-semibold">{{ $r->reg_no }}</td>
                            <td>{{ $r->student_name }}</td>
                            <td>{{ $r->father_name ?? '—' }}</td>
                            <td>{{ $r->schoolClass?->name ?? '—' }}</td>
                            <td>{{ $r->course?->name ?? '—' }}</td>
                            <td>{{ $r->creator?->name ?? '—' }}</td>
                            <td class="text-end">
                                <a href="{{ route('monthlyreports.edit', $r->id) }}"
                                   class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('monthlyreports.destroy', $r->id) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this report?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">No monthly reports yet.</td></tr>
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
