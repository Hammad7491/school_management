@extends('layouts.app')

@section('content')
@php $isEdit = isset($report); @endphp
<div class="container" style="max-width:900px">

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="mb-0">{{ $isEdit ? 'Edit Monthly Report' : 'Add Monthly Report' }}</h2>
        <a href="{{ route('monthlyreports.index') }}" class="btn btn-link">Back to list →</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ $isEdit ? route('monthlyreports.update', $report->id) : route('monthlyreports.store') }}"
          method="POST" class="card shadow-sm">
        @csrf @if($isEdit) @method('PUT') @endif

        <div class="card-body">
            <div class="row g-3">

                <div class="col-md-4">
                    <label class="form-label">Report Date</label>
                    <input type="date" name="report_date" class="form-control"
                           value="{{ old('report_date', $isEdit && $report->report_date ? $report->report_date->format('Y-m-d') : '') }}"
                           required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Student Reg #</label>
                    <input type="text" name="reg_no" class="form-control"
                           value="{{ old('reg_no', $report->reg_no ?? '') }}" required>
                    <small class="text-muted">Enter the 6‑digit student registration number.</small>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Father Name</label>
                    <input type="text" name="father_name" class="form-control"
                           value="{{ old('father_name', $report->father_name ?? '') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Student Name</label>
                    <input type="text" name="student_name" class="form-control"
                           value="{{ old('student_name', $report->student_name ?? '') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Remarks (optional)</label>
                    <textarea name="remarks" rows="3" class="form-control">{{ old('remarks', $report->remarks ?? '') }}</textarea>
                </div>

                {{-- Assign to Class --}}
                <div class="col-md-6">
                    @php $classChecked = old('add_class', $isEdit && $report->class_id !== null) ? true : false; @endphp
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="add_class" name="add_class" value="1" {{ $classChecked ? 'checked' : '' }}>
                        <label class="form-check-label" for="add_class">Assign to Class</label>
                    </div>
                    <select name="class_id" id="class_id" class="form-control" style="display:none;">
                        <option value="">— Select Class —</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ old('class_id', $report->class_id ?? null) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Assign to Course --}}
                <div class="col-md-6">
                    @php $courseChecked = old('add_course', $isEdit && $report->course_id !== null) ? true : false; @endphp
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="add_course" name="add_course" value="1" {{ $courseChecked ? 'checked' : '' }}>
                        <label class="form-check-label" for="add_course">Assign to Course</label>
                    </div>
                    <select name="course_id" id="course_id" class="form-control" style="display:none;">
                        <option value="">— Select Course —</option>
                        @foreach($courses as $co)
                            <option value="{{ $co->id }}" {{ old('course_id', $report->course_id ?? null) == $co->id ? 'selected' : '' }}>{{ $co->name }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-primary" type="submit">{{ $isEdit ? 'Update Report' : 'Add Report' }}</button>
                @if($isEdit)
                    <a href="{{ route('monthlyreports.create') }}" class="btn btn-outline-secondary">Cancel Edit</a>
                @endif
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const addClass  = document.getElementById('add_class');
    const classSel  = document.getElementById('class_id');
    const addCourse = document.getElementById('add_course');
    const courseSel = document.getElementById('course_id');

    function toggle(chk, sel){ sel.style.display = chk.checked ? 'block' : 'none'; }

    // initial (handles edit & validation back)
    toggle(addClass, classSel);
    toggle(addCourse, courseSel);

    addClass?.addEventListener('change',  () => toggle(addClass, classSel));
    addCourse?.addEventListener('change', () => toggle(addCourse, courseSel));
});
</script>
@endsection
