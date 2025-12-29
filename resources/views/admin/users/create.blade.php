{{-- resources/views/admin/users/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container my-5">
  <div class="card shadow-lg border-0 rounded-3">
    <div class="card-header bg-primary text-white d-flex align-items-center">
      <h3 class="mb-0">
        @if(isset($user))
          <i class="fas fa-user-edit me-2"></i>Edit User
        @else
          <i class="fas fa-user-plus me-2"></i>Add New User
        @endif
      </h3>
    </div>

    <div class="card-body">
      @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <ul class="mb-0">
            @foreach($errors->all() as $e)
              <li>{{ $e }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      <form
        action="{{ isset($user)
                   ? route('admin.users.update', $user)
                   : route('admin.users.store') }}"
        method="POST"
      >
        @csrf
        @if(isset($user)) @method('PUT') @endif

        <div class="row g-3">
          <div class="col-md-6">
            <div class="form-floating">
              <input
                type="text"
                class="form-control"
                id="name"
                name="name"
                placeholder="Name"
                value="{{ old('name', $user->name ?? '') }}"
                required
              >
              <label for="name">
                <i class="bi bi-person-fill me-1"></i>Name
              </label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-floating">
              <input
                type="email"
                class="form-control"
                id="email"
                name="email"
                placeholder="Email"
                value="{{ old('email', $user->email ?? '') }}"
                required
              >
              <label for="email">
                <i class="bi bi-envelope-fill me-1"></i>Email
              </label>
            </div>
          </div>
        </div>

        <div class="row g-3 mt-3">
          <div class="col-md-6">
            <div class="form-floating">
              <input
                type="password"
                class="form-control"
                id="password"
                name="password"
                placeholder="Password"
                {{ isset($user) ? '' : 'required' }}
              >
              <label for="password">
                <i class="bi bi-lock-fill me-1"></i>
                {{ isset($user)
                   ? 'New Password (leave blank to keep)'
                   : 'Password' }}
              </label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-floating">
              <input
                type="password"
                class="form-control"
                id="password_confirmation"
                name="password_confirmation"
                placeholder="Confirm Password"
                {{ isset($user) ? '' : 'required' }}
              >
              <label for="password_confirmation">
                <i class="bi bi-lock-fill me-1"></i>Confirm Password
              </label>
            </div>
          </div>
        </div>

        <div class="mt-4">
          <label for="roles" class="form-label fw-bold">
            <i class="fas fa-user-shield me-1"></i>Roles <span class="text-danger">*</span>
          </label>
          <p class="text-muted small mb-2">Hold Ctrl (Windows) or Cmd (Mac) to select multiple roles</p>
          
          <div class="border rounded p-3" style="background-color: #f8f9fa;">
            @foreach($roles as $role)
              <div class="form-check mb-2">
                <input 
                  class="form-check-input role-checkbox" 
                  type="checkbox" 
                  name="roles[]" 
                  value="{{ $role->name }}" 
                  id="role_{{ $role->id }}"
                  {{ in_array($role->name, old('roles', $userRoles ?? [])) ? 'checked' : '' }}
                >
                <label class="form-check-label" for="role_{{ $role->id }}">
                  <strong>{{ ucfirst($role->name) }}</strong>
                  @if($role->name === 'Admin')
                    <small class="text-muted">- Full system access</small>
                  @elseif($role->name === 'Principal')
                    <small class="text-muted">- Academic oversight</small>
                  @elseif($role->name === 'Incharge')
                    <small class="text-muted">- Class management</small>
                  @elseif($role->name === 'Teacher')
                    <small class="text-muted">- Teaching assignments</small>
                  @endif
                </label>
              </div>
            @endforeach
          </div>
          
          <div id="role-error" class="text-danger mt-2" style="display: none;">
            <small><i class="fas fa-exclamation-circle me-1"></i>Please select at least one role</small>
          </div>
        </div>

        <div class="d-flex justify-content-end mt-4 gap-2">
          <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left-circle me-1"></i>Back to List
          </a>
          <button type="submit" class="btn btn-success" id="submitBtn">
            <i class="fas fa-save me-1"></i>
            {{ isset($user) ? 'Update User' : 'Create User' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
  .form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
  }
  
  .form-check-label {
    cursor: pointer;
    user-select: none;
  }
  
  .form-check:hover {
    background-color: #e9ecef;
    padding: 4px 8px;
    margin: 0 -8px 8px -8px;
    border-radius: 4px;
  }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const form = document.querySelector('form');
  const roleCheckboxes = document.querySelectorAll('.role-checkbox');
  const roleError = document.getElementById('role-error');
  const submitBtn = document.getElementById('submitBtn');

  // Form validation
  form.addEventListener('submit', function(e) {
    const checkedRoles = Array.from(roleCheckboxes).filter(cb => cb.checked);
    
    if (checkedRoles.length === 0) {
      e.preventDefault();
      roleError.style.display = 'block';
      
      // Scroll to error
      roleError.scrollIntoView({ behavior: 'smooth', block: 'center' });
      
      // Add shake animation
      roleError.style.animation = 'shake 0.5s';
      setTimeout(() => {
        roleError.style.animation = '';
      }, 500);
      
      return false;
    } else {
      roleError.style.display = 'none';
    }
  });

  // Hide error when user selects a role
  roleCheckboxes.forEach(checkbox => {
    checkbox.addEventListener('change', function() {
      const checkedRoles = Array.from(roleCheckboxes).filter(cb => cb.checked);
      if (checkedRoles.length > 0) {
        roleError.style.display = 'none';
      }
    });
  });

  // Visual feedback on label click
  const labels = document.querySelectorAll('.form-check-label');
  labels.forEach(label => {
    label.addEventListener('click', function() {
      const checkbox = this.previousElementSibling;
      if (checkbox && checkbox.type === 'checkbox') {
        // Checkbox will be automatically toggled by the label
        setTimeout(() => {
          const checkedRoles = Array.from(roleCheckboxes).filter(cb => cb.checked);
          if (checkedRoles.length > 0) {
            roleError.style.display = 'none';
          }
        }, 10);
      }
    });
  });
});

// Add shake animation
const style = document.createElement('style');
style.textContent = `
  @keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-10px); }
    75% { transform: translateX(10px); }
  }
`;
document.head.appendChild(style);
</script>
@endsection