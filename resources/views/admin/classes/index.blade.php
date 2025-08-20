@extends('layouts.app')

@section('content')
<div class="container" style="max-width:1100px">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="mb-0">All Classes</h2>
        <a href="{{ route('classes.create') }}" class="btn btn-primary">
            + Add New Class
        </a>
    </div>

    {{-- Flash message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Table Card --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 8rem;">ID</th>
                            <th>Name</th>
                            <th style="width: 14rem;">Fee</th>
                            <th class="text-end" style="width: 16rem;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classes as $c)
                            <tr>
                                <td class="text-muted">#{{ $c->id }}</td>
                                <td class="fw-semibold">{{ $c->name }}</td>
                                <td>
                                    @if(!is_null($c->fee))
                                        {{ number_format($c->fee, 0) }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('classes.edit', $c->id) }}" class="btn btn-sm btn-outline-primary">
                                        Edit
                                    </a>
                                    <form action="{{ route('classes.destroy', $c->id) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Delete this class?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    No classes found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
