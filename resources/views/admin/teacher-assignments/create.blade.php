{{-- resources/views/admin/teacher-assignments/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container my-5">
  <div class="card shadow-lg border-0 rounded-3">
    <div class="card-header bg-primary text-white">
      <h3 class="mb-0">
        @if(isset($assignment))
          <i class="fas fa-edit me-2"></i>Edit Teacher Assignment
        @else
          <i class="fas fa-plus me-2"></i>New Teacher Assignment
        @endif
      </h3>
    </div>

    <div class="card-body">
      @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <ul class="mb-0">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      <form
        action="{{ isset($assignment) 
                   ? route('admin.teacher-assignments.update', $assignment) 
                   : route('admin.teacher-assignments.store') }}"
        method="POST"
      >
        @csrf
        @if(isset($assignment)) @method('PUT') @endif

        <div class="row g-3">
          {{-- Teacher Selection --}}
          <div class="col-md-6">
            <label for="teacher_id" class="form-label">
              <i class="fas fa-user-tie me-1"></i>Select Teacher <span class="text-danger">*</span>
            </label>
            <select id="teacher_id" name="teacher_id" class="form-select" required>
              <option value="">-- Select Teacher --</option>
              @foreach($teachers as $teacher)
                <option value="{{ $teacher->id }}" 
                  {{ old('teacher_id', $assignment->teacher_id ?? '') == $teacher->id ? 'selected' : '' }}>
                  {{ $teacher->name }} ({{ $teacher->email }})
                </option>
              @endforeach
            </select>
          </div>

          {{-- Class Selection --}}
          <div class="col-md-6">
            <label for="class_id" class="form-label">
              <i class="fas fa-school me-1"></i>Select Class <span class="text-danger">*</span>
            </label>
            <select id="class_id" name="class_id" class="form-select" required>
              <option value="">-- Select Class --</option>
              @foreach($classes as $class)
                <option value="{{ $class->id }}" 
                  {{ old('class_id', $assignment->class_id ?? '') == $class->id ? 'selected' : '' }}>
                  {{ $class->name }}
                </option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="row g-3 mt-3">
          {{-- Designation --}}
          <div class="col-md-6">
            <label for="designation" class="form-label">
              <i class="fas fa-user-tag me-1"></i>Designation <span class="text-danger">*</span>
            </label>
            <select id="designation" name="designation" class="form-select" required>
              <option value="">-- Select Designation --</option>
              <option value="incharge" 
                {{ old('designation', $assignment->designation ?? '') == 'incharge' ? 'selected' : '' }}>
                Incharge
              </option>
              <option value="subject_teacher" 
                {{ old('designation', $assignment->designation ?? '') == 'subject_teacher' ? 'selected' : '' }}>
                Subject Teacher
              </option>
            </select>
          </div>

          {{-- Assignment Type --}}
          <div class="col-md-6">
            <label class="form-label">
              <i class="fas fa-book me-1"></i>Assignment Type <span class="text-danger">*</span>
            </label>
            <div class="d-flex gap-3">
              <div class="form-check">
                <input 
                  class="form-check-input" 
                  type="radio" 
                  name="assignment_type" 
                  id="type_subject" 
                  value="subject"
                  {{ old('assignment_type', $assignment->assignment_type ?? '') == 'subject' ? 'checked' : '' }}
                  required
                >
                <label class="form-check-label" for="type_subject">
                  Subject
                </label>
              </div>
              <div class="form-check">
                <input 
                  class="form-check-input" 
                  type="radio" 
                  name="assignment_type" 
                  id="type_course" 
                  value="course"
                  {{ old('assignment_type', $assignment->assignment_type ?? '') == 'course' ? 'checked' : '' }}
                  required
                >
                <label class="form-check-label" for="type_course">
                  Course
                </label>
              </div>
            </div>
          </div>
        </div>

        {{-- Subject Selection (conditional) --}}
        <div class="row g-3 mt-3" id="subject_section" style="display: none;">
          <div class="col-12">
            <label for="subject_id" class="form-label">
              <i class="fas fa-book-open me-1"></i>Select Subject <span class="text-danger">*</span>
            </label>
            <select id="subject_id" name="subject_id" class="form-select">
              <option value="">-- Select Subject --</option>
              @foreach($subjects as $subject)
                <option value="{{ $subject->id }}" 
                  {{ old('subject_id', $assignment->subject_id ?? '') == $subject->id ? 'selected' : '' }}>
                  {{ $subject->name }}
                </option>
              @endforeach
            </select>
          </div>
        </div>

        {{-- Course Selection (conditional) --}}
        <div class="row g-3 mt-3" id="course_section" style="display: none;">
          <div class="col-12">
            <label for="course_id" class="form-label">
              <i class="fas fa-graduation-cap me-1"></i>Select Course <span class="text-danger">*</span>
            </label>
            <select id="course_id" name="course_id" class="form-select">
              <option value="">-- Select Course --</option>
              @foreach($courses as $course)
                <option value="{{ $course->id }}" 
                  {{ old('course_id', $assignment->course_id ?? '') == $course->id ? 'selected' : '' }}>
                  {{ $course->name }}
                </option>
              @endforeach
            </select>
          </div>
        </div>

        @if(isset($assignment))
          <div class="row g-3 mt-3">
            <div class="col-12">
              <div class="form-check form-switch">
                <input 
                  class="form-check-input" 
                  type="checkbox" 
                  id="is_active" 
                  name="is_active" 
                  value="1"
                  {{ old('is_active', $assignment->is_active ?? true) ? 'checked' : '' }}
                >
                <label class="form-check-label" for="is_active">
                  Active Assignment
                </label>
              </div>
            </div>
          </div>
        @endif

        <div class="d-flex justify-content-end mt-4 gap-2">
          <a href="{{ route('admin.teacher-assignments.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left-circle me-1"></i>Cancel
          </a>
          <button type="submit" class="btn btn-success">
            <i class="fas fa-save me-1"></i>
            {{ isset($assignment) ? 'Update Assignment' : 'Create Assignment' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const typeSubject = document.getElementById('type_subject');
  const typeCourse = document.getElementById('type_course');
  const subjectSection = document.getElementById('subject_section');
  const courseSection = document.getElementById('course_section');
  const subjectSelect = document.getElementById('subject_id');
  const courseSelect = document.getElementById('course_id');

  function toggleSections() {
    if (typeSubject.checked) {
      subjectSection.style.display = 'block';
      courseSection.style.display = 'none';
      subjectSelect.required = true;
      courseSelect.required = false;
      courseSelect.value = '';
    } else if (typeCourse.checked) {
      subjectSection.style.display = 'none';
      courseSection.style.display = 'block';
      subjectSelect.required = false;
      courseSelect.required = true;
      subjectSelect.value = '';
    }
  }

  typeSubject.addEventListener('change', toggleSections);
  typeCourse.addEventListener('change', toggleSections);

  // Initialize on page load
  toggleSections();
});
</script>
@endsection