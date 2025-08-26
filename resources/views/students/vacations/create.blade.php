@extends('students.layouts.app')

@section('content')
<div class="container" style="max-width:800px">
    <h2 class="mb-3">New Leave Request</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('student.vacationrequests.store') }}" method="POST" class="row g-3">
                @csrf

                <div class="col-md-6">
                    <label class="form-label">Student</label>
                    <input type="text" class="form-control" value="{{ $student->name }}" disabled>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Reg #</label>
                    <input type="text" class="form-control" value="{{ $student->reg_no }}" disabled>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Class</label>
                    <input type="text" class="form-control" value="{{ $student->schoolClass->name ?? '—' }}" disabled>
                </div>

                <div class="col-md-6">
                    <label class="form-label">From (optional)</label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">To (optional)</label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}" class="form-control">
                </div>

                <div class="col-12">
                    <label class="form-label">Reason / Description *</label>
                    <textarea name="reason" class="form-control" rows="5" required>{{ old('reason') }}</textarea>
                </div>

                <div class="col-12 d-flex gap-2">
                    <button class="btn btn-primary" type="submit">Submit Request</button>
                    <a class="btn btn-light" href="{{ route('student.vacationrequests.index') }}">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
