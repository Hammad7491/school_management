{{-- resources/views/admin/subjects/index.blade.php --}}
@extends('layouts.app')

@push('styles')
<style>
  /* Header layout */
  .page-head{
    display:flex;
    gap:.75rem;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
  }

  /* Truncate long text nicely */
  .truncate{
    display:block;
    max-width:520px;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
  }

  /* Action buttons (fix tiny/empty-looking buttons) */
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

  /* Small screens */
  @media (max-width: 576px){
    .page-wrap{ padding-left:.75rem; padding-right:.75rem; }
    .page-title{ font-size:1.2rem; }
    .head-actions{ width:100%; }
    .head-actions .btn{ width:100%; justify-content:center; }
    .truncate{ max-width:100%; }
  }

  /* Mobile card layout */
  .subject-card{
    border:1px solid rgba(0,0,0,.08);
    border-radius:.75rem;
    padding:1rem;
  }
  .subject-meta{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:.75rem;
  }
  .subject-actions{
    display:flex;
    gap:.5rem;
    flex-wrap:wrap;
    justify-content:flex-end;
  }
</style>
@endpush

@section('content')
<div class="container-fluid my-4 my-md-5 page-wrap">
  <div class="row justify-content-center">
    <div class="col-12 col-xxl-10">

      {{-- Header --}}
      <div class="page-head mb-3 mb-md-4">
        <h2 class="mb-0 page-title d-flex align-items-center">
          <i class="bi bi-book-half me-2"></i>
          Subjects
        </h2>

        {{-- ✅ show only if user can create subjects --}}
        @can('create subjects')
          <div class="head-actions">
            <a href="{{ route('subjects.create') }}" class="btn btn-primary d-inline-flex align-items-center">
              <i class="bi bi-plus-lg me-1"></i>
              <span>Add Subject</span>
            </a>
          </div>
        @endcan
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

          @if($subjects->isEmpty())
            <div class="text-center text-muted py-5">
              <i class="bi bi-book-half fs-1 mb-3"></i>
              <div class="fw-semibold">No subjects found.</div>

              {{-- ✅ show CTA only if can create --}}
              @can('create subjects')
                <div class="mt-2">
                  <a href="{{ route('subjects.create') }}" class="btn btn-sm btn-primary">
                    + Add Subject
                  </a>
                </div>
              @endcan
            </div>
          @else

            {{-- MOBILE VIEW (cards) --}}
            <div class="d-md-none">
              <div class="vstack gap-3">
                @foreach($subjects as $subject)
                  <div class="subject-card">
                    <div class="subject-meta">
                      <div class="min-w-0">
                        <div class="text-muted small mb-1">#{{ $subject->id }}</div>

                        <div class="fw-semibold mb-1">
                          {{ $subject->name }}
                        </div>

                        <div class="text-muted small">
                          {{ \Illuminate\Support\Str::limit($subject->description, 120) }}
                        </div>

                        <div class="mt-2">
                          <span class="badge {{ $subject->status ? 'bg-success' : 'bg-secondary' }}">
                            {{ $subject->status ? 'Active' : 'Inactive' }}
                          </span>
                        </div>
                      </div>

                      {{-- ✅ Actions shown only if edit/delete permission exists --}}
                      <div class="subject-actions flex-shrink-0">
                        @can('edit subjects')
                          <a
                            href="{{ route('subjects.edit', $subject) }}"
                            class="btn btn-sm btn-outline-primary action-btn"
                            title="Edit"
                            aria-label="Edit subject"
                          >
                            <i class="bi bi-pencil-fill"></i>
                          </a>
                        @endcan

                        @can('delete subjects')
                          <form
                            action="{{ route('subjects.destroy', $subject) }}"
                            method="POST"
                            class="m-0"
                            onsubmit="return confirm('Delete this subject?')"
                          >
                            @csrf
                            @method('DELETE')
                            <button
                              type="submit"
                              class="btn btn-sm btn-outline-danger action-btn"
                              title="Delete"
                              aria-label="Delete subject"
                            >
                              <i class="bi bi-trash-fill"></i>
                            </button>
                          </form>
                        @endcan

                        {{-- If no actions --}}
                        @cannot('edit subjects')
                          @cannot('delete subjects')
                            <span class="text-muted small">—</span>
                          @endcannot
                        @endcannot
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>

            {{-- TABLET/DESKTOP VIEW (table) --}}
            <div class="d-none d-md-block">
              <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                  <thead class="table-light">
                    <tr>
                      <th style="min-width:70px;">#</th>
                      <th style="min-width:180px;">Name</th>
                      <th style="min-width:320px;">Description</th>
                      <th style="min-width:120px;">Status</th>
                      <th class="text-end" style="min-width:140px;">Actions</th>
                    </tr>
                  </thead>

                  <tbody>
                    @foreach($subjects as $subject)
                      <tr>
                        <td>{{ $subject->id }}</td>

                        <td class="fw-semibold">{{ $subject->name }}</td>

                        <td>
                          <span class="truncate">
                            {{ \Illuminate\Support\Str::limit($subject->description, 80) }}
                          </span>
                        </td>

                        <td>
                          <span class="badge {{ $subject->status ? 'bg-success' : 'bg-secondary' }}">
                            {{ $subject->status ? 'Active' : 'Inactive' }}
                          </span>
                        </td>

                        <td class="text-end">
                          @canany(['edit subjects','delete subjects'])
                            <div class="d-inline-flex align-items-center gap-2 justify-content-end">

                              @can('edit subjects')
                                <a
                                  href="{{ route('subjects.edit', $subject) }}"
                                  class="btn btn-sm btn-outline-primary action-btn"
                                  title="Edit"
                                  aria-label="Edit subject"
                                >
                                  <i class="bi bi-pencil-fill"></i>
                                </a>
                              @endcan

                              @can('delete subjects')
                                <form
                                  action="{{ route('subjects.destroy', $subject) }}"
                                  method="POST"
                                  class="m-0"
                                  onsubmit="return confirm('Delete this subject?')"
                                >
                                  @csrf
                                  @method('DELETE')
                                  <button
                                    type="submit"
                                    class="btn btn-sm btn-outline-danger action-btn"
                                    title="Delete"
                                    aria-label="Delete subject"
                                  >
                                    <i class="bi bi-trash-fill"></i>
                                  </button>
                                </form>
                              @endcan

                            </div>
                          @else
                            <span class="text-muted">—</span>
                          @endcanany
                        </td>
                      </tr>
                    @endforeach
                  </tbody>

                </table>
              </div>
            </div>

          @endif
        </div>
      </div>

    </div>
  </div>
</div>
@endsection
