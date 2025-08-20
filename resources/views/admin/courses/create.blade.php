@extends('layouts.app')

@section('content')
<div class="container" style="max-width:900px">

    <h2 class="mb-3">{{ isset($course) ? 'Edit Course' : 'Add New Course' }}</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    @php $isEdit = isset($course); @endphp
    <form action="{{ $isEdit ? route('courses.update', $course->id) : route('courses.store') }}"
          method="POST" enctype="multipart/form-data" class="card shadow-sm">
        @csrf
        @if($isEdit) @method('PUT') @endif

        <div class="card-body">

            <div class="mb-3">
                <label class="form-label">Course Name</label>
                <input type="text" name="name" class="form-control"
                       value="{{ old('name', $course->name ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Course Fee</label>
                <input type="number" name="fee" step="0.01" class="form-control"
                       value="{{ old('fee', $course->fee ?? '') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Course Description</label>
                <textarea name="description" rows="4" class="form-control"
                          placeholder="Write a short description...">{{ old('description', $course->description ?? '') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Course Image</label>
                <input type="file" name="image" class="form-control">
                @if(!empty($course?->image_path))
                    <div class="mt-2">
                        <img src="{{ asset('storage/'.$course->image_path) }}" alt="Course image"
                             style="height:80px;border-radius:6px;">
                        <a href="{{ asset('storage/'.$course->image_path) }}" download class="btn btn-sm btn-outline-secondary ms-2">
                            Download current image
                        </a>
                    </div>
                @endif
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    {{ $isEdit ? 'Update Course' : 'Add Course' }}
                </button>
                @if($isEdit)
                    <a href="{{ route('courses.create') }}" class="btn btn-outline-secondary">Cancel Edit</a>
                @endif
                <a href="{{ route('courses.index') }}" class="btn btn-link ms-auto">View Courses List</a>
            </div>
        </div>
    </form>
</div>
@endsection
