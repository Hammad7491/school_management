{{-- resources/views/admin/vacations/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Vacation Requests</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>Student</th>
                <th>Reg #</th>
                <th>Class</th>
                <th style="width:40%;">Reason</th>
                <th>Status</th>
                <th style="width:220px;">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($requests as $r)
                <tr>
                    <td>{{ $r->student_name }}</td>
                    <td>{{ $r->reg_no }}</td>
                    <td>{{ $r->class?->name }}</td>

                    {{-- Reason (truncated with modal for full view) --}}
                    <td style="white-space:normal; word-wrap:break-word;">
                        {{ Str::limit($r->reason, 100) }}
                        @if(strlen($r->reason) > 100)
                            <a href="#" data-bs-toggle="modal" data-bs-target="#reasonModal{{ $r->id }}">Read more</a>
                        @endif
                    </td>

                    {{-- Status --}}
                    <td>
                        @php $status = ucfirst(strtolower($r->status)); @endphp
                        @if($status === 'Pending')
                            <span class="badge bg-warning text-dark">Pending</span>
                        @elseif($status === 'Approved')
                            <span class="badge bg-success">Approved</span>
                        @elseif($status === 'Rejected')
                            <span class="badge bg-danger">Rejected</span>
                        @endif
                    </td>

                    {{-- Actions --}}
                    <td>
                        @if($status === 'Pending')
                            {{-- Accept --}}
                            <form method="POST"
                                  action="{{ route('admin.vacations.updateStatus', [$r->id, 'approved']) }}"
                                  class="d-inline"
                                  onsubmit="return confirm('Accept this request?');">
                                @csrf
                                <button class="btn btn-sm btn-success">
                                    <i class="bi bi-check-circle"></i> Accept
                                </button>
                            </form>

                            {{-- Reject --}}
                            <form method="POST"
                                  action="{{ route('admin.vacations.updateStatus', [$r->id, 'rejected']) }}"
                                  class="d-inline"
                                  onsubmit="return confirm('Reject this request?');">
                                @csrf
                                <button class="btn btn-sm btn-danger">
                                    <i class="bi bi-x-circle"></i> Reject
                                </button>
                            </form>
                        @else
                            <span class="badge bg-secondary">Final: {{ $status }}</span>
                        @endif
                    </td>
                </tr>

                {{-- Modal for full reason --}}
                <div class="modal fade" id="reasonModal{{ $r->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Full Reason ({{ $r->student_name }})</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body" style="white-space:pre-wrap; word-wrap:break-word;">
                                <p>{{ $r->reason }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No vacation requests yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
