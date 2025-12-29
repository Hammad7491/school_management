{{-- resources/views/admin/subjects/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container my-5">
  <div class="card shadow-lg border-0 rounded-3">
    <div class="card-header bg-primary text-white">
      <h3 class="mb-0">
        @if(isset($subject))
          <i class="fas fa-edit me-2"></i>Edit Subject
        @else
          <i class="fas fa-plus me-2"></i>Add Subject
        @endif
      </h3>
    </div>

    <div class="card-body">
      @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
          <ul class="mb-0">
            @foreach($errors->all() as $e)
              <li>{{ $e }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      <form
        action="{{ isset($subject) ? route('subjects.update', $subject) : route('subjects.store') }}"
        method="POST"
      >
        @csrf
        @if(isset($subject)) @method('PUT') @endif

        <div class="mb-3">
          <div class="form-floating">
            <input
              type="text"
              class="form-control"
              id="name"
              name="name"
              placeholder="Subject Name"
              value="{{ old('name', $subject->name ?? '') }}"
              required
            >
            <label for="name">
              <i class="bi bi-book me-1"></i>Subject Name
            </label>
          </div>
        </div>

        <div class="mb-3">
          <label for="description" class="form-label">
            <i class="bi bi-text-paragraph me-1"></i>Description
          </label>
          <textarea
            class="form-control"
            id="description"
            name="description"
            rows="4"
            placeholder="Enter subject description..."
          >{{ old('description', $subject->description ?? '') }}</textarea>
        </div>

        <div class="d-flex justify-content-end gap-2">
          <a href="{{ route('subjects.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left-circle me-1"></i>Back
          </a>
          <button type="submit" class="btn btn-success">
            <i class="fas fa-save me-1"></i>
            {{ isset($subject) ? 'Update' : 'Create' }} Subject
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection