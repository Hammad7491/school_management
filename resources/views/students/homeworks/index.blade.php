@extends('students.layouts.app')

@section('content')
<div class="container" style="max-width:1100px">
  <h2 class="mb-3">Homework Schedule</h2>

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Day</th><th>Class</th><th>Course</th><th>Comment</th><th>File</th>
          </tr>
        </thead>
        <tbody>
          @forelse($homeworks as $h)
            <tr>
              <td>{{ optional($h->day)->format('Y-m-d') }}</td>
              <td>{{ $h->schoolClass?->name ?? '—' }}</td>
              <td>{{ $h->course?->name ?? '—' }}</td>
              <td>{{ $h->comment ?? '—' }}</td>
              <td>
                @if($h->file_path)
                  <a href="{{ route('homeworks.download',$h->id) }}">{{ $h->file_name ?? 'Download' }}</a>
                @else — @endif
              </td>
            </tr>
          @empty
            <tr><td colspan="5" class="text-center text-muted py-4">No homework yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="card-footer">{{ $homeworks->links() }}</div>
  </div>
</div>
@endsection
