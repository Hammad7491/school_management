@extends('layouts.app')

@section('content')
@php
    $isEdit   = isset($student);
    $student  = $student ?? null;
@endphp

<style>
  :root{
    --bg:#f7f9ff; --ink:#0b1020; --muted:#6b7280; --card:#ffffff; --stroke:rgba(15,23,42,.10);
    --brand1:#6a7bff; --brand2:#22d3ee; --ring:rgba(106,123,255,.28); --danger:#e11d48; --radius:18px;
  }
  @media (prefers-color-scheme: dark){
    :root{ --bg:#0b1020; --ink:#e6e9f5; --muted:#9aa3ba; --card:#0f1830; --stroke:rgba(255,255,255,.12); --ring:rgba(106,123,255,.45); }
  }

  .page{ min-height:100dvh; background:
      radial-gradient(800px 400px at -10% 0%, rgba(106,123,255,.12), transparent 60%),
      radial-gradient(700px 500px at 110% -10%, rgba(34,211,238,.12), transparent 60%),
      var(--bg); color:var(--ink); }
  .wrap{ max-width:980px; margin:0 auto; padding:28px 14px 72px; }

  .title{ font-size:clamp(28px,5vw,56px); font-weight:900; line-height:1.05; margin:4px 0 8px; }
  .title span{ background:linear-gradient(90deg,var(--brand1),var(--brand2)); -webkit-background-clip:text; background-clip:text; color:transparent; }
  .sub{ color:var(--muted); margin-bottom:16px; }

  .topbar{ display:flex; gap:12px; align-items:center; justify-content:space-between; flex-wrap:wrap; }

  .cardx{ background:var(--card); border:1px solid var(--stroke); border-radius:var(--radius); box-shadow:0 20px 55px rgba(2,6,23,.08); }
  .cardx .body{ padding:22px; }

  .grid{ display:grid; gap:16px; grid-template-columns:1fr; }
  @media (min-width:760px){ .grid{ grid-template-columns:1fr 1fr; } }
  @media (min-width:1024px){ .grid-3{ grid-template-columns:1fr 1fr 1fr; } }

  .label{ font-weight:800; margin-bottom:8px; display:flex; align-items:center; gap:.35rem; }
  .req{ color:var(--danger); font-weight:900; }
  .hint{ font-size:12px; color:var(--muted); margin-top:6px; }

  .input, .select, .textarea{
    width:100%; padding:14px; border-radius:14px; border:1px solid var(--stroke); background:#fff; color:inherit;
    outline:none; transition:.18s border-color, .18s box-shadow;
  }
  .textarea{ min-height:96px; resize:vertical; }
  .input:focus, .select:focus, .textarea:focus{ border-color:var(--brand1); box-shadow:0 0 0 6px var(--ring); }

  .uploader{ position:relative; border:1px dashed var(--stroke); border-radius:14px; background:rgba(106,123,255,.04);
    display:flex; gap:14px; align-items:center; padding:14px; cursor:pointer; }
  .uploader:hover{ box-shadow:0 0 0 6px var(--ring); }
  .uploader input[type=file]{ position:absolute; inset:0; opacity:0; cursor:pointer; }
  .uploader .thumb{ width:72px; height:72px; border-radius:12px; object-fit:cover; border:1px solid var(--stroke); background:#fff; }

  .actions{ display:flex; flex-wrap:wrap; gap:12px; align-items:center; margin-top:10px; }
  .btn{ border:0; border-radius:12px; padding:12px 16px; font-weight:900; cursor:pointer; }
  .btn-primary{ color:#fff; background:linear-gradient(90deg,var(--brand1),var(--brand2)); box-shadow:0 12px 28px rgba(106,123,255,.35); }
  .btn-primary:hover{ filter:brightness(1.05); transform:translateY(-1px); }
  .btn-outline{ background:transparent; color:var(--ink); border:1px solid var(--stroke); }

  .chip{ padding:6px 10px; border-radius:999px; border:1px solid var(--stroke); cursor:pointer; font-size:13px; }
  .btn-check{ display:none; }
</style>

<div class="page">
  <div class="wrap">

    <div class="topbar">
      <h1 class="title">{{ $isEdit ? 'Edit' : 'Add New' }} <span>Student</span></h1>
      <a href="{{ route('students.index') }}" class="btn-link">← Back to Students</a>
    </div>

    <p class="sub">Fill student details. Fields marked with <span class="req">*</span> are required.</p>

    @if ($errors->any())
      <div class="alert-danger" role="alert">
        <ul style="margin:0 0 0 1rem;">
          @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ $isEdit ? route('students.update', $student?->id) : route('students.store') }}"
          method="POST" class="cardx" enctype="multipart/form-data">
      @csrf
      @if($isEdit) @method('PUT') @endif

      <div class="body">

        {{-- Top Trio --}}
        <div class="grid grid-3">
          <div>
            <label class="label">Registration #</label>
            <input type="text" class="input" value="{{ $isEdit ? $student->reg_no : 'Auto on save' }}" disabled>
          </div>

          <div>
            <label class="label">Admission Date</label>
            <input type="date" name="admission_date" class="input"
              value="{{ old('admission_date', $student?->admission_date?->format('Y-m-d')) }}">
          </div>

          <div>
            <label class="label">D.O.B</label>
            <input type="date" name="dob" class="input"
              value="{{ old('dob', $student?->dob?->format('Y-m-d')) }}">
          </div>
        </div>

        {{-- ACCOUNT --}}
        <div class="grid" style="margin-top:6px;">
          <div>
            <label class="label">Student Name <span class="req">*</span></label>
            <input type="text" name="name" class="input" required
              value="{{ old('name', $student?->name) }}">
          </div>

          <div>
            <label class="label">Email <span class="req">*</span></label>
            <input type="email" name="email" class="input" required
              value="{{ old('email', $student?->email ?? $student?->account?->email) }}">
          </div>

          <div>
            <label class="label">{{ $isEdit ? 'Change Password (optional)' : 'Password *' }}</label>
            <input type="password" name="password" class="input" {{ $isEdit ? '' : 'required' }}>
          </div>

          <div>
            <label class="label">Father Name <span class="req">*</span></label>
            <input type="text" name="father_name" class="input" required
              value="{{ old('father_name', $student?->father_name) }}">
          </div>

          <div>
            <label class="label">Caste</label>
            <input type="text" name="caste" class="input"
              value="{{ old('caste', $student?->caste) }}">
          </div>

          <div>
            <label class="label">Parents Number</label>
            <input type="text" name="parent_phone" class="input"
              value="{{ old('parent_phone', $student?->parent_phone) }}">
          </div>

          <div>
            <label class="label">Guardian Number</label>
            <input type="text" name="guardian_phone" class="input"
              value="{{ old('guardian_phone', $student?->guardian_phone) }}">
          </div>

          <div style="grid-column:1/-1;">
            <label class="label">Address</label>
            <textarea name="address" class="textarea">{{ old('address', $student?->address) }}</textarea>
          </div>
        </div>

        {{-- STUDENT PROFILE IMAGE --}}
        <div style="margin-top:16px;">
          <label class="label">Student Profile Picture</label>
          <div class="uploader">
            <img id="pPreview" class="thumb"
              src="{{ $isEdit && $student?->profile_image_path ? asset('storage/'.$student->profile_image_path) : '' }}"
              onerror="this.style.display='none'">
            <div>
              <strong>Click to upload</strong>
              <div class="meta">PNG/JPG up to ~4MB</div>
            </div>
            <input type="file" name="profile_image" id="profile_image" accept="image/*">
          </div>
          @if($isEdit && $student?->profile_image_path)
            <div class="hint">Current file: <a href="{{ asset('storage/'.$student->profile_image_path) }}" download>Download image</a></div>
          @endif
        </div>

        {{-- B-FORM IMAGE --}}
        <div style="margin-top:16px;">
          <label class="label">B-Form Image</label>
          <div class="uploader">
            <img id="bPreview" class="thumb"
              src="{{ $isEdit && $student?->b_form_image_path ? asset('storage/'.$student->b_form_image_path) : '' }}"
              onerror="this.style.display='none'">
            <div>
              <strong>Click to upload</strong>
              <div class="meta">PNG/JPG/PDF up to ~5MB</div>
            </div>
            <input type="file" name="b_form_image" id="b_form_image" accept="image/*,.pdf">
          </div>
          @if($isEdit && $student?->b_form_image_path)
            <div class="hint">Current file:
              <a href="{{ route('students.bform.download',$student->id) }}">Download image</a>
            </div>
          @endif
        </div>

        <hr style="margin:20px 0; border-top:1px solid var(--stroke);">

        {{-- CLASS --}}
        <div>
          <label class="label">Add in Class</label>
          <select name="class_id" id="class_id" class="select">
            <option value="">— Select Class —</option>
            @foreach($classes as $c)
              <option value="{{ $c->id }}"
                {{ (string)old('class_id', $student?->class_id) === (string)$c->id ? 'selected':'' }}>
                {{ $c->name }}
              </option>
            @endforeach
          </select>
        </div>

        {{-- COURSE --}}
        <div style="margin-top:10px;">
          <label class="label">Add in Course</label>
          <select name="course_id" id="course_id" class="select">
            <option value="">— Select Course —</option>
            @foreach($courses as $co)
              <option value="{{ $co->id }}"
                {{ (string)old('course_id', $student?->course_id) === (string)$co->id ? 'selected':'' }}>
                {{ $co->name }}
              </option>
            @endforeach
          </select>
        </div>

        {{-- STATUS (EDIT ONLY) --}}
        @if($isEdit)
          <div style="margin-top:16px;">
            <label class="label">Status</label>

            <div class="seg">
              <input type="radio" class="btn-check" name="status" id="st_null"
                     value="" {{ $student->status===null ? 'checked':'' }}>
              <label class="chip" for="st_null">Pending</label>

              <input type="radio" class="btn-check" name="status" id="st_appr"
                     value="1" {{ $student->status===1 ? 'checked':'' }}>
              <label class="chip" for="st_appr">Approved</label>

              <input type="radio" class="btn-check" name="status" id="st_rej"
                     value="0" {{ $student->status===0 ? 'checked':'' }}>
              <label class="chip" for="st_rej">Rejected</label>
            </div>
          </div>
        @endif

        {{-- ACTIONS --}}
        <div class="actions">
          <button class="btn btn-primary" type="submit">
            {{ $isEdit ? 'Update Student' : 'Add Student' }}
          </button>

          @if($isEdit)
            <a href="{{ route('students.create') }}" class="btn btn-outline">Cancel Edit</a>
          @endif

          <a href="{{ route('students.index') }}" class="btn-link" style="margin-left:auto">
            View Students →
          </a>
        </div>

      </div>
    </form>

  </div>
</div>

<script>
  // Preview B-Form
  const bInput = document.getElementById('b_form_image');
  if (bInput) {
    bInput.addEventListener('change', function(){
      const img = document.getElementById('bPreview');
      if (this.files && this.files[0] && img) {
        img.src = URL.createObjectURL(this.files[0]);
        img.style.display='block';
      }
    });
  }

  // Preview Profile Photo
  const pInput = document.getElementById('profile_image');
  if (pInput) {
    pInput.addEventListener('change', function(){
      const img = document.getElementById('pPreview');
      if (this.files && this.files[0] && img) {
        img.src = URL.createObjectURL(this.files[0]);
        img.style.display='block';
      }
    });
  }
</script>

@endsection
