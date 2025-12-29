{{-- resources/views/admin/subjects/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container my-5">
  <div class="d-flexjustify-content-between align-items-center mb-4">
<h2 class="mb-0"><i class="fas fa-book-open me-2"></i>Subjects</h2>
<a href="{{ route('subjects.create') }}" class="btn btn-primary">
<i class="fas fa-plus me-1"></i>Add Subject
</a>
  </div>
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
{{ session('success') }}
<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
  <div class="card shadow-sm border-0">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Description</th>
              <th>Status</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($subjects as $subject)
              <tr>
                <td>{{ $subject->id }}</td>
                <td><strong>{{ $subject->name }}</strong></td>
                <td>{{ Str::limit($subject->description, 50) }}</td>
                <td>
                  <span class="badge {{ $subject->status ? 'bg-success' : 'bg-secondary' }}">
                    {{ $subject->status ? 'Active' : 'Inactive' }}
                  </span>
                </td>
                <td class="text-end">
                  <div class="btn-group">
                    <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-sm btn-outline-primary">
                      <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('subjects.destroy', $subject) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Delete this subject?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center text-muted py-4">No subjects found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
