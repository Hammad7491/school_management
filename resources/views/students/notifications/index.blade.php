@extends('students.layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">All Notifications</h3>
        <a href="{{ route('student.dashboard') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <ul class="list-group list-group-flush">
                @forelse($notifications as $n)
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="me-auto">
                            <h6 class="fw-bold mb-1">{{ $n->title }}</h6>
                            <p class="mb-1 text-muted small">
                                {{ \Illuminate\Support\Str::limit(strip_tags($n->body), 150) }}
                            </p>
                            <span class="badge bg-light text-dark">
                                {{ $n->published_at?->diffForHumans() }}
                            </span>
                        </div>
                        <span class="text-primary">
                            <iconify-icon icon="mdi:bullhorn-outline" class="fs-4"></iconify-icon>
                        </span>
                    </li>
                @empty
                    <li class="list-group-item text-center text-muted">
                        No notifications available.
                    </li>
                @endforelse
            </ul>
        </div>

        @if($notifications->hasPages())
            <div class="card-footer">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
