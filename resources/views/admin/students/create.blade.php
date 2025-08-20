@extends('layouts.app')

@section('content')
@php $isEdit = isset($student); @endphp
<div class="container" style="max-width:900px">

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="mb-0">{{ $isEdit ? 'Edit Student' : 'Add New Student' }}</h2>
        <a href="{{ route('students.index') }}" class="btn btn-link">Back to Students</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <form action="{{ $isEdit ? route('students.update',$student->id) : route('students.store') }}"
          method="POST" class="card shadow-sm" enctype="multipart/form-data">
        @csrf @if($isEdit) @method('PUT') @endif

        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Registration #</label>
                    <input type="text" class="form-control" value="{{ $isEdit ? $student->reg_no : 'Auto on save' }}" disabled>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Admission Date</label>
                    <input type="date" name="admission_date" class="form-control" value="{{ old('admission_date', $student->admission_date ?? '') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">D.O.B</label>
                    <input type="date" name="dob" class="form-control" value="{{ old('dob', $student->dob ?? '') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Student Name</label>
                    <input type="text" name="name" class="form-control" required value="{{ old('name', $student->name ?? '') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Father Name</label>
                    <input type="text" name="father_name" class="form-control" required value="{{ old('father_name', $student->father_name ?? '') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">B-Form Image</label>
                    <input type="file" name="b_form_image" class="form-control">
                    @if($isEdit && $student->b_form_image_path)
                        <small class="text-muted d-block mt-1">
                            Current: <a href="{{ route('students.bform.download', $student->id) }}">Download image</a>
                        </small>
                    @endif
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ $isEdit ? 'Change Password (optional)' : 'Password' }}</label>
                    <input type="password" name="password" class="form-control" {{ $isEdit ? '' : 'required' }}>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Caste</label>
                    <input type="text" name="caste" class="form-control" value="{{ old('caste', $student->caste ?? '') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Parents Number</label>
                    <input type="text" name="parent_phone" class="form-control" value="{{ old('parent_phone', $student->parent_phone ?? '') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Guardian Number</label>
                    <input type="text" name="guardian_phone" class="form-control" value="{{ old('guardian_phone', $student->guardian_phone ?? '') }}">
                </div>

                <div class="col-12">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control" rows="2">{{ old('address', $student->address ?? '') }}</textarea>
                </div>
            </div>

            <hr class="my-4">

            {{-- Add in Class --}}
            @php $addClass = old('add_class', isset($student) && !is_null($student->class_id)); @endphp
            <div class="mb-3">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="add_class" name="add_class" value="1" {{ $addClass ? 'checked' : '' }}>
                    <label class="form-check-label" for="add_class">Add in Class</label>
                </div>
                <select name="class_id" id="class_id" class="form-control" style="display:none;">
                    <option value="">— Select Class —</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ old('class_id', $student->class_id ?? null) == $c->id ? 'selected' : '' }}>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Add in Course --}}
            @php $addCourse = old('add_course', isset($student) && !is_null($student->course_id)); @endphp
            <div class="mb-3">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="add_course" name="add_course" value="1" {{ $addCourse ? 'checked' : '' }}>
                    <label class="form-check-label" for="add_course">Add in Course</label>
                </div>
                <select name="course_id" id="course_id" class="form-control" style="display:none;">
                    <option value="">— Select Course —</option>
                    @foreach($courses as $co)
                        <option value="{{ $co->id }}" {{ old('course_id', $student->course_id ?? null) == $co->id ? 'selected' : '' }}>
                            {{ $co->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Status (edit) --}}
            @if($isEdit)
                <div class="mb-3">
                    <label class="form-label d-block">Status</label>
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" name="status" id="st_null" value=""
                               {{ $student->status === null ? 'checked' : '' }}>
                        <label class="btn btn-outline-secondary" for="st_null">Pending</label>

                        <input type="radio" class="btn-check" name="status" id="st_appr" value="1"
                               {{ $student->status === 1 ? 'checked' : '' }}>
                        <label class="btn btn-outline-success" for="st_appr">Approved</label>

                        <input type="radio" class="btn-check" name="status" id="st_rej" value="0"
                               {{ $student->status === 0 ? 'checked' : '' }}>
                        <label class="btn btn-outline-danger" for="st_rej">Rejected</label>
                    </div>
                </div>
            @endif

            <div class="d-flex gap-2">
                <button class="btn btn-primary" type="submit">{{ $isEdit ? 'Update Student' : 'Add Student' }}</button>
                @if($isEdit)
                    <a href="{{ route('students.create') }}" class="btn btn-outline-secondary">Cancel Edit</a>
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
    toggle(addClass, classSel);
    toggle(addCourse, courseSel);

    addClass.addEventListener('change',  () => toggle(addClass, classSel));
    addCourse.addEventListener('change', () => toggle(addCourse, courseSel));
});
</script>
@endsection
