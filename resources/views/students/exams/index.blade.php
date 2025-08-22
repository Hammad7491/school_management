@extends('students.layouts.app')

@section('content')
<div class="container" style="max-width:1100px">
  <h2 class="mb-3">Exam Schedule</h2>

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Class</th><th>Course</th><th>Comment</th><th>File</th>
          </tr>
        </thead>
        <tbody>
          @forelse($exams as $e)
            <tr>
              <td>{{ $e->schoolClass?->name ?? '—' }}</td>
              <td>{{ $e->course?->name ?? '—' }}</td>
              <td>{{ $e->comment ?? '—' }}</td>
              <td>
                @if($e->file_path)
                  <a href="{{ route('exams.download',$e->id) }}">Download</a>
                @else — @endif
              </td>
            </tr>
          @empty
            <tr><td colspan="4" class="text-center text-muted py-4">No exams yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="card-footer">{{ $exams->links() }}</div>
  </div>
</div>
@endsection
