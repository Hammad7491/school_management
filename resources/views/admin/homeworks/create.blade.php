@extends('layouts.app')

@section('content')
@php $isEdit = isset($homework); @endphp
<div class="container" style="max-width:900px">

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="mb-0">{{ $isEdit ? 'Edit Homework' : 'Add Homework' }}</h2>
        <a href="{{ route('homeworks.index') }}" class="btn btn-link">Back to List</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <form action="{{ $isEdit ? route('homeworks.update',$homework->id) : route('homeworks.store') }}"
          method="POST" class="card shadow-sm" enctype="multipart/form-data">
        @csrf @if($isEdit) @method('PUT') @endif

        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Day (Date)</label>
                    <input type="date" name="day" class="form-control"
                           value="{{ old('day', $homework->day ?? '') }}" required>
                </div>

                <div class="col-md-8">
                    <label class="form-label">Comment</label>
                    <textarea name="comment" class="form-control" rows="3">{{ old('comment', $homework->comment ?? '') }}</textarea>
                </div>

                <div class="col-md-6">
                    <div class="form-check mb-2">
                        @php $addClass = old('add_class', isset($homework) && !is_null($homework->class_id)); @endphp
                        <input class="form-check-input" type="checkbox" id="add_class" name="add_class" value="1" {{ $addClass ? 'checked' : '' }}>
                        <label class="form-check-label" for="add_class">Assign to Class</label>
                    </div>
                    <select name="class_id" id="class_id" class="form-control" style="display:none;">
                        <option value="">— Select Class —</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ old('class_id', $homework->class_id ?? null) == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <div class="form-check mb-2">
                        @php $addCourse = old('add_course', isset($homework) && !is_null($homework->course_id)); @endphp
                        <input class="form-check-input" type="checkbox" id="add_course" name="add_course" value="1" {{ $addCourse ? 'checked' : '' }}>
                        <label class="form-check-label" for="add_course">Assign to Course</label>
                    </div>
                    <select name="course_id" id="course_id" class="form-control" style="display:none;">
                        <option value="">— Select Course —</option>
                        @foreach($courses as $co)
                            <option value="{{ $co->id }}" {{ old('course_id', $homework->course_id ?? null) == $co->id ? 'selected' : '' }}>
                                {{ $co->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label">Upload (Image/PDF)</label>
                    <input type="file" name="file" class="form-control">
                    @if($isEdit && $homework->file_path)
                        <small class="text-muted d-block mt-1">
                            Current: <a href="{{ route('homeworks.download', $homework->id) }}">Download file</a>
                        </small>
                    @endif
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-primary" type="submit">{{ $isEdit ? 'Update Homework' : 'Add Homework' }}</button>
                @if($isEdit)
                    <a href="{{ route('homeworks.create') }}" class="btn btn-outline-secondary">Cancel Edit</a>
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
