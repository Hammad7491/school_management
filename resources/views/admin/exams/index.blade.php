@extends('layouts.app')

@section('content')
<div class="container" style="max-width:1100px">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="mb-0">Exams</h2>
        @can('create exams')
        <a class="btn btn-primary" href="{{ route('exams.create') }}">+ Add Exam</a>
        @endcan
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Class</th>
                        <th>Course</th>
                        <th>Comment</th>
                        <th>File</th>
                        <th style="width:160px">Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($exams as $ex)
                    <tr>
                        <td>{{ $ex->schoolClass?->name ?? '—' }}</td>
                        <td>{{ $ex->course?->name ?? '—' }}</td>
                        <td class="text-truncate" style="max-width:340px">{{ $ex->comment }}</td>
                        <td>
                            @if($ex->file_path)
                                <a href="{{ route('exams.download', $ex->id) }}">Download</a>
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                @can('edit exams')
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('exams.edit', $ex->id) }}">Edit</a>
                                @endcan
                                @can('delete exams')
                                <form action="{{ route('exams.destroy', $ex->id) }}" method="POST"
                                      onsubmit="return confirm('Delete this exam?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center p-4 text-muted">No exams yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{ $exams->links() }}
        </div>
    </div>
</div>
@endsection
