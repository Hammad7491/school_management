@extends('layouts.app')

@section('content')
<div class="container" style="max-width:1200px">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="mb-0">Homeworks</h2>
        <a href="{{ route('homeworks.create') }}" class="btn btn-primary">+ Add Homework</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Day</th>
                        <th>Class</th>
                        <th>Course</th>
                        <th>Comment</th>
                        <th>File</th>
                        <th>Created By</th>
                        <th class="text-end" style="width:14rem;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($homeworks as $h)
                        <tr>
                            <td class="fw-semibold">{{ \Carbon\Carbon::parse($h->day)->format('Y-m-d') }}</td>
                            <td>{{ $h->schoolClass->name ?? '—' }}</td>
                            <td>{{ $h->course->name ?? '—' }}</td>
                            <td>{{ $h->comment ?? '—' }}</td>
                            <td>
                                @if($h->file_path)
                                    <a href="{{ route('homeworks.download', $h->id) }}">
                                        {{ $h->file_name ?? 'Download' }}
                                    </a>
                                @else
                                    —
                                @endif
                            </td>
                            <td>{{ $h->user->name ?? '—' }}</td>
                            <td class="text-end">
                                <a href="{{ route('homeworks.edit',$h->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('homeworks.destroy',$h->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this homework?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">No homeworks found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $homeworks->links() }}
        </div>
    </div>
</div>
@endsection
