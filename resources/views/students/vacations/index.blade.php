@extends('students.layouts.app')

@section('content')
<div class="container" style="max-width:1000px">
    <h2 class="mb-3">My Leave Requests</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-3">
        <div class="card-body d-flex justify-content-between flex-wrap">
            <div>
                <h5 class="mb-1">{{ $student->name }}</h5>
                <div class="small text-muted">Reg #: {{ $student->reg_no }}</div>
            </div>
            <a href="{{ route('student.vacationrequests.create') }}" class="btn btn-primary btn-sm">New Request</a>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead>
                <tr>
                    <th>Date(s)</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th class="text-end">Submitted</th>
                </tr>
                </thead>
                <tbody>
                @forelse($requests as $r)
                    <tr>
                        <td>
                            @php
                                $range = $r->start_date ? $r->start_date->format('Y-m-d') : '—';
                                if ($r->end_date) $range .= ' → '.$r->end_date->format('Y-m-d');
                            @endphp
                            {{ $range }}
                        </td>
                        <td style="max-width:520px">{{ Str::limit($r->reason, 120) }}</td>
                        <td>
                            @if($r->status === 'approved')
                                <span class="badge text-bg-success">Approved</span>
                            @elseif($r->status === 'rejected')
                                <span class="badge text-bg-danger">Rejected</span>
                            @else
                                <span class="badge text-bg-secondary">Pending</span>
                            @endif
                        </td>
                        <td class="text-end">{{ $r->created_at->diffForHumans() }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted">No requests yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($requests->hasPages())
            <div class="card-footer">{{ $requests->links() }}</div>
        @endif
    </div>
</div>
@endsection
