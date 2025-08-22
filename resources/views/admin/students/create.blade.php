@extends('layouts.app')

@section('content')
@php
    $isEdit  = isset($student);
    $student = $student ?? null;
@endphp

<style>
  /* —— Premium-simple styling (same design system as your other pages) —— */
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
  .input[disabled]{ background:rgba(2,6,23,.03); color:var(--muted); }

  .check-row{ display:flex; gap:10px; align-items:center; margin-bottom:8px; }
  .reveal{ display:none; }
  .reveal.show{ display:block; animation:fadeIn .18s ease-out; }
  @keyframes fadeIn{ from{opacity:0; transform:translateY(-2px);} to{opacity:1; transform:none;} }

  .uploader{ position:relative; border:1px dashed var(--stroke); border-radius:14px; background:rgba(106,123,255,.04);
    display:flex; gap:14px; align-items:center; padding:14px; }
  .uploader:hover{ box-shadow:0 0 0 6px var(--ring); }
  .uploader input[type=file]{ position:absolute; inset:0; opacity:0; cursor:pointer; }
  .uploader .thumb{ width:72px; height:72px; border-radius:12px; object-fit:cover; border:1px solid var(--stroke); background:#fff; }
  .uploader .meta{ color:var(--muted); font-size:12px; }

  .actions{ display:flex; flex-wrap:wrap; gap:12px; align-items:center; margin-top:10px; }
  .btn{ border:0; border-radius:12px; padding:12px 16px; font-weight:900; cursor:pointer; }
  .btn-primary{ color:#fff; background:linear-gradient(90deg,var(--brand1),var(--brand2)); box-shadow:0 12px 28px rgba(106,123,255,.35); }
  .btn-primary:hover{ filter:brightness(1.05); transform:translateY(-1px); }
  .btn-outline{ background:transparent; color:var(--ink); border:1px solid var(--stroke); }
  .btn-link{ text-decoration:none; color:var(--brand1); }
  .seg{ display:inline-flex; gap:8px; flex-wrap:wrap; }
  .seg .chip{ padding:10px 12px; border-radius:12px; border:1px solid var(--stroke); background:transparent; font-weight:800; cursor:pointer; }
  input.btn-check:checked + .chip{ border-color:var(--brand1); box-shadow:0 0 0 6px var(--ring); }
  .alert-danger{ background:#fef2f2; border:1px solid #fecaca; color:#7f1d1d; border-radius:12px; padding:12px 14px; }
  @media (prefers-color-scheme: dark){
    .input, .select, .textarea{ background:rgba(255,255,255,.02); }
    .uploader .thumb{ background:transparent; }
    .alert-danger{ background:rgba(225,29,72,.16); border-color:rgba(225,29,72,.45); color:#ffe4e6; }
  }
</style>

<div class="page">
  <div class="wrap">
    <div class="topbar">
      <h1 class="title">{{ $isEdit ? 'Edit' : 'Add New' }} <span>Student</span></h1>
      <a href="{{ route('students.index') }}" class="btn-link">← Back to Students</a>
    </div>
    <p class="sub">Fill student details. Account email/password are for login. Fields marked <span class="req">*</span> are required.</p>

    @if ($errors->any())
      <div class="alert-danger" role="alert">
        <ul style="margin:0 0 0 1rem;">
          @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ $isEdit ? route('students.update', $student?->id) : route('students.store') }}"
          method="POST" class="cardx" enctype="multipart/form-data" novalidate>
      @csrf
      @if($isEdit) @method('PUT') @endif

      <div class="body">
        {{-- Top trio --}}
        <div class="grid grid-3">
          <div>
            <label class="label" for="reg">Registration #</label>
            <input id="reg" type="text" class="input" value="{{ $isEdit ? ($student?->reg_no ?? '') : 'Auto on save' }}" disabled>
          </div>

          <div>
            <label class="label" for="admission_date">Admission Date</label>
            <input id="admission_date" type="date" name="admission_date" class="input"
                   value="{{ old('admission_date', $student?->admission_date?->format('Y-m-d') ?? '') }}">
          </div>

          <div>
            <label class="label" for="dob">D.O.B</label>
            <input id="dob" type="date" name="dob" class="input"
                   value="{{ old('dob', $student?->dob?->format('Y-m-d') ?? '') }}">
          </div>
        </div>

        {{-- Account --}}
        <div class="grid" style="margin-top:6px;">
          <div>
            <label class="label" for="name">Student Name <span class="req">*</span></label>
            <input id="name" type="text" name="name" class="input" required
                   value="{{ old('name', $student?->name ?? '') }}">
          </div>

          <div>
            <label class="label" for="email">Email (account login) <span class="req">*</span></label>
            <input id="email" type="email" name="email" class="input" required
                   value="{{ old('email', $student?->email ?? ($student?->account?->email ?? '')) }}">
          </div>

          <div>
            <label class="label" for="password">{{ $isEdit ? 'Change Password (optional)' : 'Password (users table)' }} {!! $isEdit ? '' : '<span class="req">*</span>' !!}</label>
            <input id="password" type="password" name="password" class="input" {{ $isEdit ? '' : 'required' }}>
          </div>

          <div>
            <label class="label" for="father_name">Father Name <span class="req">*</span></label>
            <input id="father_name" type="text" name="father_name" class="input" required
                   value="{{ old('father_name', $student?->father_name ?? '') }}">
          </div>

          <div>
            <label class="label" for="caste">Caste</label>
            <input id="caste" type="text" name="caste" class="input" value="{{ old('caste', $student?->caste ?? '') }}">
          </div>

          <div>
            <label class="label" for="parent_phone">Parents Number</label>
            <input id="parent_phone" type="text" name="parent_phone" class="input" value="{{ old('parent_phone', $student?->parent_phone ?? '') }}">
          </div>

          <div>
            <label class="label" for="guardian_phone">Guardian Number</label>
            <input id="guardian_phone" type="text" name="guardian_phone" class="input" value="{{ old('guardian_phone', $student?->guardian_phone ?? '') }}">
          </div>

          <div style="grid-column:1/-1;">
            <label class="label" for="address">Address</label>
            <textarea id="address" name="address" class="textarea" rows="2">{{ old('address', $student?->address ?? '') }}</textarea>
          </div>
        </div>

        {{-- B-Form Image --}}
        <div style="margin-top:6px;">
          <label class="label" for="b_form_image">B-Form Image</label>
          <div class="uploader">
            <img id="bPreview" class="thumb" src="{{ $isEdit && $student?->b_form_image_path ? asset('storage/'.$student->b_form_image_path) : '' }}" alt="" onerror="this.style.display='none'">
            <div>
              <strong>Click to upload</strong>
              <div class="meta">PNG/JPG up to ~5MB</div>
            </div>
            <input id="b_form_image" type="file" name="b_form_image" accept="image/*">
          </div>
          @if($isEdit && $student?->b_form_image_path)
            <div class="hint" style="margin-top:6px;">
              Current file: <a href="{{ route('students.bform.download', $student?->id) }}">Download image</a>
            </div>
          @endif
        </div>

        <hr style="margin:20px 0; border:0; border-top:1px solid var(--stroke);">

        {{-- Add in Class --}}
        @php $addClass = old('add_class', $student?->class_id !== null); @endphp
        <div>
          <div class="check-row">
            <input class="form-check-input" type="checkbox" id="add_class" name="add_class" value="1" {{ $addClass ? 'checked' : '' }}>
            <label for="add_class" class="label" style="margin:0;">Add in Class</label>
          </div>
          <select name="class_id" id="class_id" class="select reveal" {{ $addClass ? 'style=display:block' : '' }}>
            <option value="">— Select Class —</option>
            @foreach($classes as $c)
              <option value="{{ $c->id }}" {{ old('class_id', $student?->class_id) == $c->id ? 'selected' : '' }}>
                {{ $c->name }}
              </option>
            @endforeach
          </select>
        </div>

        {{-- Add in Course --}}
        @php $addCourse = old('add_course', $student?->course_id !== null); @endphp
        <div style="margin-top:10px;">
          <div class="check-row">
            <input class="form-check-input" type="checkbox" id="add_course" name="add_course" value="1" {{ $addCourse ? 'checked' : '' }}>
            <label for="add_course" class="label" style="margin:0;">Add in Course</label>
          </div>
          <select name="course_id" id="course_id" class="select reveal" {{ $addCourse ? 'style=display:block' : '' }}>
            <option value="">— Select Course —</option>
            @foreach($courses as $co)
              <option value="{{ $co->id }}" {{ old('course_id', $student?->course_id) == $co->id ? 'selected' : '' }}>
                {{ $co->name }}
              </option>
            @endforeach
          </select>
        </div>

        {{-- Status (edit only) --}}
        @if($isEdit)
          <div style="margin-top:16px;">
            <label class="label">Status</label>
            <div class="seg" role="group" aria-label="Status">
              <input type="radio" class="btn-check" name="status" id="st_null" value="" {{ $student?->status === null ? 'checked' : '' }}>
              <label class="chip" for="st_null">Pending</label>

              <input type="radio" class="btn-check" name="status" id="st_appr" value="1" {{ $student?->status === 1 ? 'checked' : '' }}>
              <label class="chip" for="st_appr">Approved</label>

              <input type="radio" class="btn-check" name="status" id="st_rej" value="0" {{ $student?->status === 0 ? 'checked' : '' }}>
              <label class="chip" for="st_rej">Rejected</label>
            </div>
          </div>
        @endif

        <div class="actions">
          <button class="btn btn-primary" type="submit">{{ $isEdit ? 'Update Student' : 'Add Student' }}</button>
          @if($isEdit)
            <a href="{{ route('students.create') }}" class="btn btn-outline">Cancel edit</a>
          @endif
          <a href="{{ route('students.index') }}" class="btn-link" style="margin-left:auto">View students list →</a>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
  // Toggle selects when checkboxes change + require when visible
  (function(){
    const pairs = [
      {chk:'#add_class',  sel:'#class_id'},
      {chk:'#add_course', sel:'#course_id'}
    ];
    pairs.forEach(({chk, sel}) => {
      const c = document.querySelector(chk);
      const s = document.querySelector(sel);
      if(!c || !s) return;
      function apply(){ s.classList.toggle('show', c.checked); s.required = c.checked; }
      apply(); c.addEventListener('change', apply);
    });
  })();

  // Live preview for B-form image
  (function(){
    const input = document.getElementById('b_form_image');
    const prev  = document.getElementById('bPreview');
    if(!input) return;
    input.addEventListener('change', () => {
      const file = input.files && input.files[0];
      if(!file) return;
      const url = URL.createObjectURL(file);
      if(prev){ prev.src = url; prev.style.display = 'block'; }
    });
  })();
</script>
@endsection
