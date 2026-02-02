{{-- resources/views/admin/teacher-assignments/index.blade.php --}}
@extends('layouts.app')

@push('styles')
<style>
  /* ========== Responsive upgrades only (no logic changed) ========== */

  /* Page header */
  .page-head{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:.75rem;
    flex-wrap:wrap;
  }
  .page-title{
    display:flex;
    align-items:center;
    gap:.5rem;
  }

  /* Avoid overflow in flex children */
  .min-w-0{ min-width:0 !important; }

  /* Avatar */
  .avatar-sm{
    width:40px;
    height:40px;
    font-size:16px;
    font-weight:700;
    flex:0 0 40px;
  }

  /* Truncate helpers */
  .truncate-1{
    display:block;
    max-width: min(360px, 62vw);
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
  }
  .truncate-2{
    display:-webkit-box;
    -webkit-line-clamp:2;
    -webkit-box-orient:vertical;
    overflow:hidden;
    word-break:break-word;
  }

  /* Action buttons */
  .action-btn{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    width:38px;
    height:34px;
    padding:0;
    line-height:1;
  }
  .action-btn i{ font-size:1rem; line-height:1; }

  /* Status toggle button alignment */
  .toggle-btn{ white-space:nowrap; }

  /* Card list for mobile */
  .assign-card{
    border:1px solid rgba(0,0,0,.08);
    border-radius:.9rem;
    padding:1rem;
    background:#fff;
  }
  .assign-top{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:.75rem;
  }
  .assign-actions{
    display:flex;
    gap:.5rem;
    flex-wrap:wrap;
    justify-content:flex-end;
  }

  .meta-grid{
    display:grid;
    grid-template-columns: 1fr 1fr;
    gap:.65rem .75rem;
    margin-top:.9rem;
  }
  .meta-label{
    font-size:.78rem;
    color:#6c757d;
    margin-bottom:.15rem;
  }

  /* Table responsiveness (tablet+): allow scroll without breaking page */
  .table-responsive{
    -webkit-overflow-scrolling: touch;
    overflow-x:auto;
  }
  /* On medium screens, table may need more width; scroll handles it */
  .teacher-table{
    min-width: 980px;
  }

  /* Small screens */
  @media (max-width: 576px){
    .page-wrap{ padding-left:.75rem; padding-right:.75rem; }
    .head-actions{ width:100%; }
    .head-actions .btn{ width:100%; justify-content:center; }
    .meta-grid{ grid-template-columns: 1fr; }
    .truncate-1{ max-width: 100%; }
  }

  /* Extra small phones (320-375px) */
  @media (max-width: 420px){
    .avatar-sm{
      width:34px; height:34px; flex:0 0 34px;
      font-size:14px;
    }
    .action-btn{
      width:34px;
      height:32px;
    }
  }

  /* Large screens: keep table comfortable */
  @media (min-width: 1400px){
    .teacher-table{ min-width: 1100px; }
  }
</style>
@endpush

@section('content')
<div class="container-fluid my-4 my-md-5 page-wrap">
  <div class="row justify-content-center">
    <div class="col-12 col-xxl-11">

      {{-- Header --}}
      <div class="page-head mb-3 mb-md-4">
        <h2 class="mb-0 page-title">
          <i class="bi bi-person-workspace"></i>
          Teacher Assignments
        </h2>

        <div class="head-actions">
          <a href="{{ route('admin.teacher-assignments.create') }}" class="btn btn-primary d-inline-flex align-items-center">
            <i class="bi bi-plus-lg me-1"></i>
            <span>New Assignment</span>
          </a>
        </div>
      </div>

      {{-- Alert --}}
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
          <div class="flex-grow-1">{{ session('success') }}</div>
          <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <div class="card shadow-sm border-0 overflow-hidden">
        <div class="card-body p-3 p-md-4">

          @if($assignments->count() === 0)
            <div class="text-center text-muted py-5">
              <i class="bi bi-inbox fs-1 mb-3 d-block"></i>
              <div class="fw-semibold">No teacher assignments found.</div>
            </div>
          @else

            {{-- ✅ MOBILE VIEW (cards): xs + sm only --}}
            <div class="d-md-none">
              <div class="vstack gap-3">
                @foreach($assignments as $assignment)
                  @php
                    $initial = mb_strtoupper(mb_substr($assignment->teacher->name ?? 'T', 0, 1));
                  @endphp

                  <div class="assign-card">
                    <div class="assign-top">
                      <div class="d-flex align-items-start gap-3 min-w-0">
                        <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center">
                          {{ $initial }}
                        </div>

                        <div class="min-w-0">
                          <div class="fw-semibold mb-0 truncate-1">
                            {{ $assignment->teacher->name }}
                          </div>
                          <div class="text-muted small truncate-1">
                            {{ $assignment->teacher->email }}
                          </div>

                          <div class="mt-2 d-flex flex-wrap gap-2">
                            <span class="badge bg-info text-dark">
                              {{ $assignment->schoolClass->name }}
                            </span>

                            <span class="badge {{ $assignment->assignment_type === 'subject' ? 'bg-success' : 'bg-warning text-dark' }}">
                              {{ ucfirst($assignment->assignment_type) }}
                            </span>

                            <span class="badge {{ $assignment->designation === 'incharge' ? 'bg-primary' : 'bg-secondary' }}">
                              {{ ucfirst(str_replace('_', ' ', $assignment->designation)) }}
                            </span>
                          </div>
                        </div>
                      </div>

                      <div class="assign-actions flex-shrink-0">
                        <a
                          href="{{ route('admin.teacher-assignments.edit', $assignment) }}"
                          class="btn btn-sm btn-outline-primary action-btn"
                          title="Edit"
                          aria-label="Edit assignment"
                        >
                          <i class="bi bi-pencil-fill"></i>
                        </a>

                        <form
                          action="{{ route('admin.teacher-assignments.destroy', $assignment) }}"
                          method="POST"
                          class="m-0"
                          onsubmit="return confirm('Delete this assignment?')"
                        >
                          @csrf
                          @method('DELETE')
                          <button
                            type="submit"
                            class="btn btn-sm btn-outline-danger action-btn"
                            title="Delete"
                            aria-label="Delete assignment"
                          >
                            <i class="bi bi-trash-fill"></i>
                          </button>
                        </form>
                      </div>
                    </div>

                    <div class="meta-grid">
                      <div>
                        <div class="meta-label">Subject / Course</div>
                        <div class="fw-semibold truncate-2">
                          {{ $assignment->getAssignedItemName() }}
                        </div>
                      </div>

                      <div>
                        <div class="meta-label">Status</div>
                        <form action="{{ route('admin.teacher-assignments.toggle-status', $assignment) }}" method="POST" class="m-0">
                          @csrf
                          <button type="submit" class="btn btn-sm toggle-btn {{ $assignment->is_active ? 'btn-success' : 'btn-secondary' }}">
                            <i class="bi bi-{{ $assignment->is_active ? 'check2' : 'x' }} me-1"></i>
                            {{ $assignment->is_active ? 'Active' : 'Inactive' }}
                          </button>
                        </form>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>

            {{-- ✅ TABLET/DESKTOP VIEW (table): md and above --}}
            <div class="d-none d-md-block">
              <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 teacher-table">
                  <thead class="table-light">
                    <tr>
                      <th style="min-width:70px;">#</th>
                      <th style="min-width:260px;">Teacher</th>
                      <th style="min-width:140px;">Class</th>
                      <th style="min-width:120px;">Type</th>
                      <th style="min-width:240px;">Subject/Course</th>
                      <th style="min-width:150px;">Designation</th>
                      <th style="min-width:160px;">Status</th>
                      <th class="text-end" style="min-width:130px;">Actions</th>
                    </tr>
                  </thead>

                  <tbody>
                    @foreach($assignments as $assignment)
                      @php
                        $initial = mb_strtoupper(mb_substr($assignment->teacher->name ?? 'T', 0, 1));
                      @endphp
                      <tr>
                        <td>{{ $assignment->id }}</td>

                        <td>
                          <div class="d-flex align-items-center gap-2 min-w-0">
                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center">
                              {{ $initial }}
                            </div>
                            <div class="min-w-0">
                              <div class="fw-semibold truncate-1">{{ $assignment->teacher->name }}</div>
                              <small class="text-muted truncate-1 d-block">{{ $assignment->teacher->email }}</small>
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

                        <td>
                          <span class="truncate-1">{{ $assignment->getAssignedItemName() }}</span>
                        </td>

                        <td>
                          <span class="badge {{ $assignment->designation === 'incharge' ? 'bg-primary' : 'bg-secondary' }}">
                            {{ ucfirst(str_replace('_', ' ', $assignment->designation)) }}
                          </span>
                        </td>

                        <td>
                          <form action="{{ route('admin.teacher-assignments.toggle-status', $assignment) }}" method="POST" class="d-inline m-0">
                            @csrf
                            <button type="submit" class="btn btn-sm toggle-btn {{ $assignment->is_active ? 'btn-success' : 'btn-secondary' }}">
                              <i class="bi bi-{{ $assignment->is_active ? 'check2' : 'x' }} me-1"></i>
                              {{ $assignment->is_active ? 'Active' : 'Inactive' }}
                            </button>
                          </form>
                        </td>

                        <td class="text-end">
                          <div class="d-inline-flex align-items-center gap-2 justify-content-end">
                            <a
                              href="{{ route('admin.teacher-assignments.edit', $assignment) }}"
                              class="btn btn-sm btn-outline-primary action-btn"
                              title="Edit"
                              aria-label="Edit assignment"
                            >
                              <i class="bi bi-pencil-fill"></i>
                            </a>

                            <form
                              action="{{ route('admin.teacher-assignments.destroy', $assignment) }}"
                              method="POST"
                              class="m-0"
                              onsubmit="return confirm('Delete this assignment?')"
                            >
                              @csrf
                              @method('DELETE')
                              <button
                                type="submit"
                                class="btn btn-sm btn-outline-danger action-btn"
                                title="Delete"
                                aria-label="Delete assignment"
                              >
                                <i class="bi bi-trash-fill"></i>
                              </button>
                            </form>
                          </div>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>

                </table>
              </div>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
              {{ $assignments->links() }}
            </div>

          @endif
        </div>
      </div>

    </div>
  </div>
</div>
@endsection
