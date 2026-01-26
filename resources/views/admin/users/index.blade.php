@extends('layouts.app')

@push('styles')
<style>
  /* Header gradient */
  .bg-gradient-primary{
    background: linear-gradient(45deg, #0d6efd, #6610f2) !important;
  }

  /* “Light primary” button */
  .btn-light-primary{
    color:#0d6efd;
    background-color:#f0f5ff;
    border:1px solid #0d6efd;
  }
  .btn-light-primary:hover{ background-color:#e2ecff; }

  /* Striped rows */
  .table-striped > tbody > tr:nth-of-type(odd){
    background-color: rgba(102,16,242,0.05);
  }

  /* Stronger table header line */
  .table thead th{ border-bottom-width:2px; }

  /* Custom badge color */
  .badge-role{ background:#6610f2; }

  /* Make badges wrap nicely */
  .roles-wrap{ display:flex; flex-wrap:wrap; gap:.35rem; }

  /* Better mobile spacing + sizing */
  @media (max-width: 576px){
    .page-wrap{ padding-left:.75rem; padding-right:.75rem; }
    .card-body{ padding:1rem !important; }
    .page-title{ font-size:1.1rem; }
    .header-actions{ width:100%; }
    .header-actions .btn{ width:100%; justify-content:center; }
    .table{ font-size:.92rem; }
  }

  /* Truncate long text nicely */
  .truncate{
    max-width: 260px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display:block;
  }
  @media (max-width: 576px){
    .truncate{ max-width: 100%; }
  }
</style>
@endpush

@section('content')
<div class="container-fluid my-4 my-md-5 page-wrap">
  <div class="row justify-content-center">
    <div class="col-12 col-xxl-10">
      <div class="card shadow border-0 rounded-3 overflow-hidden">

        {{-- Header --}}
        <div class="card-header bg-gradient-primary text-white">
          <div class="d-flex flex-column flex-sm-row gap-2 gap-sm-3 justify-content-between align-items-start align-items-sm-center">
            <h4 class="mb-0 page-title d-flex align-items-center">
              <i class="bi bi-people-fill me-2"></i>
              Users List
            </h4>

            <div class="header-actions">
              <a href="{{ route('admin.users.create') }}" class="btn btn-light-primary btn-sm d-inline-flex align-items-center">
                <i class="bi bi-person-plus-fill me-1"></i>
                <span>Add New User</span>
              </a>
            </div>
          </div>
        </div>

        <div class="card-body p-4">

          {{-- Alerts --}}
          @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
              <i class="bi bi-check-circle-fill me-2 fs-4"></i>
              <div class="flex-grow-1">{{ session('success') }}</div>
              <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          {{-- Empty state --}}
          @if($users->isEmpty())
            <div class="text-center py-5">
              <i class="bi bi-people fs-1 text-muted"></i>
              <h5 class="mt-3 mb-1">No users found</h5>
              <p class="text-muted mb-3">Create your first user to get started.</p>
              <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-person-plus-fill me-1"></i> Add New User
              </a>
            </div>
          @else

            {{-- MOBILE VIEW (cards) --}}
            <div class="d-md-none">
              <div class="vstack gap-3">
                @foreach($users as $user)
                  <div class="border rounded-3 p-3">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                      <div class="min-w-0">
                        <div class="fw-semibold mb-1">
                          <i class="bi bi-person-fill me-1 text-muted"></i>
                          {{ $user->name }}
                        </div>
                        <div class="text-muted small">
                          <i class="bi bi-envelope-fill me-1"></i>
                          <span class="truncate">{{ $user->email }}</span>
                        </div>
                      </div>

                      <div class="d-flex gap-2 flex-shrink-0">
                        <a
                          href="{{ route('admin.users.edit', $user) }}"
                          class="btn btn-sm btn-outline-primary"
                          title="Edit"
                        >
                          <i class="bi bi-pencil-fill"></i>
                        </a>

                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="m-0">
                          @csrf
                          @method('DELETE')
                          <button
                            type="submit"
                            class="btn btn-sm btn-outline-danger"
                            onclick="return confirm('Are you sure you want to delete this user?')"
                            title="Delete"
                          >
                            <i class="bi bi-trash-fill"></i>
                          </button>
                        </form>
                      </div>
                    </div>

                    <div class="mt-3">
                      <div class="small text-muted mb-1">
                        <i class="bi bi-shield-lock-fill me-1"></i> Roles
                      </div>
                      <div class="roles-wrap">
                        @forelse($user->roles as $role)
                          <span class="badge badge-role text-white rounded-pill">
                            {{ ucfirst($role->name) }}
                          </span>
                        @empty
                          <span class="text-muted small">—</span>
                        @endforelse
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>

            {{-- DESKTOP/TABLET VIEW (table) --}}
            <div class="d-none d-md-block">
              <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                  <thead class="table-light">
                    <tr>
                      <th style="min-width: 180px;">
                        <i class="bi bi-person-fill me-1"></i>Name
                      </th>
                      <th style="min-width: 240px;">
                        <i class="bi bi-envelope-fill me-1"></i>Email
                      </th>
                      <th style="min-width: 220px;">
                        <i class="bi bi-shield-lock-fill me-1"></i>Roles
                      </th>
                      <th class="text-center" style="min-width: 140px;">
                        <i class="bi bi-gear-fill me-1"></i>Actions
                      </th>
                    </tr>
                  </thead>

                  <tbody>
                    @foreach($users as $user)
                      <tr>
                        <td class="fw-semibold">{{ $user->name }}</td>

                        <td>
                          <span class="truncate">{{ $user->email }}</span>
                        </td>

                        <td>
                          <div class="roles-wrap">
                            @forelse($user->roles as $role)
                              <span class="badge badge-role text-white rounded-pill">
                                {{ ucfirst($role->name) }}
                              </span>
                            @empty
                              <span class="text-muted">—</span>
                            @endforelse
                          </div>
                        </td>

                        <td class="text-center">
                          <div class="d-inline-flex align-items-center gap-2 flex-wrap justify-content-center">
                            <a
                              href="{{ route('admin.users.edit', $user) }}"
                              class="btn btn-sm btn-outline-primary"
                              title="Edit"
                            >
                              <i class="bi bi-pencil-fill"></i>
                            </a>

                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="m-0">
                              @csrf
                              @method('DELETE')
                              <button
                                type="submit"
                                class="btn btn-sm btn-outline-danger"
                                onclick="return confirm('Are you sure you want to delete this user?')"
                                title="Delete"
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

          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
