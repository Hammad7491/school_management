@extends('layouts.app')

@section('content')
<div class="container" style="max-width:1200px">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="mb-0">Students</h2>
        <a href="{{ route('students.create') }}" class="btn btn-primary">+ Add Student</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Reg #</th>
                        <th>Name</th>
                        <th>Father</th>
                        <th>Class</th>
                        <th>Course</th>
                        <th>DOB</th>
                        <th>B-Form</th>
                        <th>Status</th>
                        <th class="text-end" style="width:15rem;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $s)
                        <tr>
                            <td class="fw-semibold">{{ $s->reg_no }}</td>
                            <td>{{ $s->name }}</td>
                            <td>{{ $s->father_name }}</td>
                            <td>{{ $s->schoolClass->name ?? '—' }}</td>
                            <td>{{ $s->course->name ?? '—' }}</td>
                            <td>{{ $s->dob ? \Carbon\Carbon::parse($s->dob)->format('Y-m-d') : '—' }}</td>
                            <td>
                                @if($s->b_form_image_path)
                                    <img src="{{ asset('storage/'.$s->b_form_image_path) }}" alt="b-form" style="height:40px;border-radius:4px;">
                                    <div><a href="{{ route('students.bform.download', $s->id) }}">Download</a></div>
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                @if ($s->status === 1)
                                    <span class="badge text-bg-success">Approved</span>
                                @elseif ($s->status === 0)
                                    <span class="badge text-bg-danger">Rejected</span>
                                @else
                                    <span class="badge text-bg-secondary">Pending</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('students.edit',$s->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('students.destroy',$s->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this student?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-muted py-4">No students found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $students->links() }}
        </div>
    </div>
</div>
@endsection
