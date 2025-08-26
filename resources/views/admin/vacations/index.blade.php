{{-- resources/views/admin/vacations/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Vacation Requests</h3>
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Student</th>
                <th>Reg #</th>
                <th>Class</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requests as $r)
            <tr>
                <td>{{ $r->student_name }}</td>
                <td>{{ $r->reg_no }}</td>
                <td>{{ $r->class?->name }}</td>
                <td>{{ $r->reason }}</td>
                <td>{{ $r->status }}</td>
                <td>
                    @if($r->status === 'Pending')
                        <form method="POST" action="{{ route('admin.vacations.updateStatus', [$r->id, 'Approved']) }}" class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-success">Approve</button>
                        </form>
                        <form method="POST" action="{{ route('admin.vacations.updateStatus', [$r->id, 'Rejected']) }}" class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-danger">Reject</button>
                        </form>
                    @else
                        <span class="badge bg-secondary">Final: {{ $r->status }}</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
