{{-- resources/views/admin/teacher-assignments/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container my-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-chalkboard-teacher me-2"></i>Teacher Assignments</h2>
    <a href="{{ route('admin.teacher-assignments.create') }}" class="btn btn-primary">
      <i class="fas fa-plus me-1"></i>New Assignment
    </a>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
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
              <th>Teacher</th>
              <th>Class</th>
              <th>Type</th>
              <th>Subject/Course</th>
              <th>Designation</th>
              <th>Status</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($assignments as $assignment)
              <tr>
                <td>{{ $assignment->id }}</td>
                <td>
                  <div class="d-flex align-items-center">
                    <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                      {{ substr($assignment->teacher->name, 0, 1) }}
                    </div>
                    <div>
                      <div class="fw-semibold">{{ $assignment->teacher->name }}</div>
                      <small class="text-muted">{{ $assignment->teacher->email }}</small>
                    </div>
                  </div>
                </td>
                <td>
                  <span class="badge bg-info text-dark">{{ $assignment->schoolClass->name }}</span>
                </td>
                <td>
                  <span class="badge {{ $assignment->assignment_type === 'subject' ? 'bg-success' : 'bg-warning text-dark' }}">
                    {{ ucfirst($assignment->assignment_type) }}
                  </span>
                </td>
                <td>{{ $assignment->getAssignedItemName() }}</td>
                <td>
                  <span class="badge {{ $assignment->designation === 'incharge' ? 'bg-primary' : 'bg-secondary' }}">
                    {{ ucfirst(str_replace('_', ' ', $assignment->designation)) }}
                  </span>
                </td>
                <td>
                  <form action="{{ route('admin.teacher-assignments.toggle-status', $assignment) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm {{ $assignment->is_active ? 'btn-success' : 'btn-secondary' }}">
                      <i class="fas fa-{{ $assignment->is_active ? 'check' : 'times' }}"></i>
                      {{ $assignment->is_active ? 'Active' : 'Inactive' }}
                    </button>
                  </form>
                </td>
                <td class="text-end">
                  <div class="btn-group" role="group">
                    <a href="{{ route('admin.teacher-assignments.edit', $assignment) }}" class="btn btn-sm btn-outline-primary">
                      <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('admin.teacher-assignments.destroy', $assignment) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Delete this assignment?')">
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
                <td colspan="8" class="text-center text-muted py-4">
                  <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                  No teacher assignments found.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="mt-3">
        {{ $assignments->links() }}
      </div>
    </div>
  </div>
</div>

<style>
.avatar-sm {
  width: 40px;
  height: 40px;
  font-size: 16px;
  font-weight: 600;
}
</style>
@endsection