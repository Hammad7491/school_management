@extends('layouts.app')

@section('content')
<div class="container" style="max-width:900px">
    <h2 class="mb-3">Results Upload</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
        </div>
    @endif
    @if(session('errors_list') && count(session('errors_list')))
        <div class="alert alert-warning">
            <strong>Row issues:</strong>
            <ul class="mb-0">
                @foreach(session('errors_list') as $msg) <li>{{ $msg }}</li> @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.results.upload') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                @csrf

                {{-- Term (always shows Mid Term, Second Term, Final Term from DB) --}}
                <div class="col-md-6">
                    <label class="form-label">Term</label>
                    <select name="term_id" class="form-control" required>
                        <option value="">— Select Term —</option>
                        @foreach($terms as $t)
                            <option value="{{ $t->id }}" {{ old('term_id') == $t->id ? 'selected' : '' }}>
                                {{ $t->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">Standard: Mid Term, Second Term, Final Term.</div>
                </div>

                {{-- Scope --}}
                <div class="col-md-6">
                    <label class="form-label">Scope</label>
                    <select name="scope" id="scope" class="form-control" required>
                        <option value="">— Select —</option>
                        <option value="class"  {{ old('scope') === 'class' ? 'selected' : '' }}>Class Result Upload</option>
                        <option value="course" {{ old('scope') === 'course' ? 'selected' : '' }}>Course Result Upload</option>
                    </select>
                </div>

                {{-- Class select --}}
                <div class="col-md-6" id="class_wrap" style="display:none;">
                    <label class="form-label">Class</label>
                    <select name="class_id" class="form-control">
                        <option value="">— Select Class —</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ old('class_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">One CSV = one class (for selected term).</div>
                </div>

                {{-- Course select --}}
                <div class="col-md-6" id="course_wrap" style="display:none;">
                    <label class="form-label">Course</label>
                    <select name="course_id" class="form-control">
                        <option value="">— Select Course —</option>
                        @foreach($courses as $c)
                            <option value="{{ $c->id }}" {{ old('course_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">One CSV = one course (for selected term).</div>
                </div>

                {{-- CSV --}}
                <div class="col-12">
                    <label class="form-label">CSV File</label>
                    <input type="file" name="csv" class="form-control" required>
                    <div class="form-text">
                        Required headers: <code>reg_no, subject, total_marks, obtained_marks</code>.
                        Optional: <code>exam_date, attendance_total, attendance_present, remarks</code>.
                    </div>
                </div>

                <div class="col-12 d-flex gap-2">
                    <button class="btn btn-primary" type="submit">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const scope = document.getElementById('scope');
    const classWrap = document.getElementById('class_wrap');
    const courseWrap = document.getElementById('course_wrap');
    function toggle(){
        classWrap.style.display = scope.value === 'class' ? 'block' : 'none';
        courseWrap.style.display = scope.value === 'course' ? 'block' : 'none';
    }
    toggle();
    scope.addEventListener('change', toggle);
});
</script>
@endsection
