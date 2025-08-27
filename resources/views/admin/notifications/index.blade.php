@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Notifications</h3>
        <a href="{{ route('admin.notifications.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> New Notification
        </a>
    </div>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Published</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notifications as $n)
                        <tr>
                            <td>
                                <strong>{{ $n->title }}</strong>
                                <div class="small text-muted">{{ \Illuminate\Support\Str::limit(strip_tags($n->body), 120) }}</div>
                            </td>
                            <td>
                                @if($n->published_at)
                                    <span class="badge bg-success">Published</span>
                                    <div class="small text-muted">{{ $n->published_at->diffForHumans() }}</div>
                                @else
                                    <span class="badge bg-secondary">Draft</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if(!$n->published_at)
                                    <form action="{{ route('admin.notifications.publish', $n) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-success"><i class="bi bi-megaphone"></i> Publish</button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.notifications.destroy', $n) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this notification?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted">No notifications yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($notifications->hasPages())
            <div class="card-footer">{{ $notifications->links() }}</div>
        @endif
    </div>
</div>
@endsection
