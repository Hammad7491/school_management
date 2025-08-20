@extends('layouts.app')

@section('content')
<div class="container" style="max-width:1100px">

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="mb-0">Courses</h2>
        <a href="{{ route('courses.create') }}" class="btn btn-primary">+ Add New Course</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th style="width:10rem;">Fee</th>
                            <th>Description</th>
                            <th style="width:12rem;">Image</th>
                            <th style="width:16rem;" class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                            <tr>
                                {{-- Name --}}
                                <td class="fw-semibold">{{ $course->name }}</td>

                                {{-- Fee (strip .00) --}}
                                <td>
                                    @if(!is_null($course->fee))
                                        {{ rtrim(rtrim($course->fee, '0'), '.') }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                {{-- Description (truncate long) --}}
                                <td class="text-truncate" style="max-width: 420px;">
                                    {{ $course->description ?? '—' }}
                                </td>

                                {{-- Image + download --}}
                                <td>
                                    @if($course->image_path)
                                        <img src="{{ asset('storage/'.$course->image_path) }}"
                                             alt="course image"
                                             style="width:70px;height:70px;object-fit:cover;border-radius:6px;display:block;">
                                        <a href="{{ asset('storage/'.$course->image_path) }}" download>Download</a>
                                    @else
                                        <span class="text-muted">No image</span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="text-end">
                                    <a href="{{ route('courses.edit', $course->id) }}"
                                       class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('courses.destroy', $course->id) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Delete this course?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No courses found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
