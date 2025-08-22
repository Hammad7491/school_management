@extends('layouts.app')

@section('content')
<style>
  /* —— Premium-simple styling to match Classes pages —— */
  .course-page{ --bg:#f7f9ff; --ink:#0b1020; --muted:#6b7280; --card:#ffffff; --stroke:rgba(15,23,42,.10);
                --brand1:#6a7bff; --brand2:#22d3ee; --danger:#e11d48; --ring:rgba(106,123,255,.28); --radius:18px; }
  @media (prefers-color-scheme: dark){
    .course-page{ --bg:#0b1020; --ink:#e6e9f5; --muted:#9aa3ba; --card:#0f1830; --stroke:rgba(255,255,255,.12); --ring:rgba(106,123,255,.45); }
  }
  .course-page{ min-height:100dvh; background: radial-gradient(800px 400px at -10% 0%, rgba(106,123,255,.12), transparent 60%),
                                        radial-gradient(700px 500px at 110% -10%, rgba(34,211,238,.12), transparent 60%), var(--bg); color:var(--ink); }
  .course-wrap{ max-width: 980px; margin: 0 auto; padding: 28px 14px 72px; }

  .title{ font-size:clamp(28px,5vw,56px); font-weight:900; line-height:1.05; margin:6px 0 12px; }
  .title span{ background:linear-gradient(90deg,var(--brand1),var(--brand2)); -webkit-background-clip:text; background-clip:text; color:transparent; }
  .sub{ color:var(--muted); margin-bottom:16px; }

  .toast{ padding:12px 14px; border-radius:12px; border:1px solid; display:flex; gap:.6rem; align-items:flex-start; margin-bottom:12px; }
  .toast-success{ background:#ecfdf5; border-color:#a7f3d0; color:#065f46; }
  .toast-danger{ background:#fef2f2; border-color:#fecaca; color:#7f1d1d; }
  @media (prefers-color-scheme: dark){
    .toast-success{ background:rgba(16,185,129,.14); border-color:rgba(16,185,129,.45); color:#d1fae5; }
    .toast-danger{ background:rgba(225,29,72,.16); border-color:rgba(225,29,72,.45); color:#ffe4e6; }
  }

  .cardx{ background:var(--card); border:1px solid var(--stroke); border-radius:var(--radius); box-shadow:0 20px 55px rgba(2,6,23,.08); padding:22px; }

  .grid{ display:grid; grid-template-columns:1fr; gap:16px; }
  @media (min-width: 760px){ .grid{ grid-template-columns: 1fr 1fr; } }

  .label{ font-weight:800; display:flex; align-items:center; gap:.35rem; margin-bottom:8px; }
  .req{ color:var(--danger); font-weight:900; }
  .hint{ font-size:12px; color:var(--muted); margin-top:8px; }

  .input, .textarea, .select { width:100%; padding:14px; border-radius:14px; border:1px solid var(--stroke); background:#fff; color:inherit; outline:none; transition:.18s border-color, .18s box-shadow; }
  .textarea{ min-height:110px; resize:vertical; }
  .input:focus, .textarea:focus, .select:focus{ border-color:#6a7bff; box-shadow:0 0 0 6px var(--ring); }

  .group{ display:flex; align-items:stretch; }
  .addon{ padding:0 12px; display:grid; place-items:center; border:1px solid var(--stroke); border-right:0; border-radius:14px 0 0 14px; color:#6b7280; background:#f7f9ff; font-weight:900; }
  .group .input{ border-radius:0 14px 14px 0; }

  .uploader{ position:relative; border:1px dashed var(--stroke); border-radius:14px; background:rgba(106,123,255,.04); display:flex; gap:14px; align-items:center; padding:14px; }
  .uploader:hover{ box-shadow:0 0 0 6px var(--ring); }
  .uploader input[type=file]{ position:absolute; inset:0; opacity:0; cursor:pointer; }
  .uploader .thumb{ width:72px; height:72px; border-radius:12px; object-fit:cover; border:1px solid var(--stroke); background:#fff; }
  .uploader .meta{ color:var(--muted); font-size:12px; }

  .actions{ display:flex; flex-wrap:wrap; gap:12px; align-items:center; margin-top:10px; }
  .btn{ border:0; border-radius:12px; padding:12px 16px; font-weight:900; cursor:pointer; }
  .btn-primary{ color:#fff; background:linear-gradient(90deg,var(--brand1),var(--brand2)); box-shadow:0 12px 28px rgba(106,123,255,.35); }
  .btn-primary:hover{ filter:brightness(1.05); transform: translateY(-1px); }
  .btn-ghost{ background:transparent; color:#6a7bff; }
  .btn-outline{ background:transparent; color:var(--ink); border:1px solid var(--stroke); }
</style>

@php $isEdit = isset($course); @endphp

<div class="course-page">
  <div class="course-wrap">
    <h1 class="title">{{ $isEdit ? 'Edit' : 'Add New' }} <span>Course</span></h1>
    <p class="sub"></p>

    @if(session('success'))
      <div class="toast toast-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
      <div class="toast toast-danger">
        <ul style="margin:0 0 0 1rem;">
          @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ $isEdit ? route('courses.update', $course->id) : route('courses.store') }}" method="POST" enctype="multipart/form-data" class="cardx" novalidate>
      @csrf
      @if($isEdit) @method('PUT') @endif

      <div class="grid">
        <div>
          <label class="label" for="name">Course Name <span class="req">*</span></label>
          <input id="name" type="text" name="name" class="input" value="{{ old('name', $course->name ?? '') }}" required>
        </div>

        <div>
          <label class="label" for="fee">Course Fee</label>
          <div class="group">
            <span class="addon">PKR</span>
            <input id="fee" type="number" name="fee" step="0.01" inputmode="decimal" min="0" class="input" placeholder="0.00" value="{{ old('fee', $course->fee ?? '') }}">
          </div>
        </div>

        <div class="" style="grid-column:1/-1;">
          <label class="label" for="description">Course Description</label>
          <textarea id="description" name="description" class="textarea" placeholder="Write a short description...">{{ old('description', $course->description ?? '') }}</textarea>
        </div>

        <div>
          <label class="label" for="image">Course Image</label>
          <div class="uploader">
            <img id="preview" class="thumb" src="{{ !empty($course?->image_path) ? asset('storage/'.$course->image_path) : '' }}" alt="" onerror="this.style.display='none'">
            <div>
              <strong>Click to upload</strong>
              <div class="meta">PNG, JPG up to ~5MB</div>
            </div>
            <input id="image" type="file" name="image" accept="image/*">
          </div>
          @if(!empty($course?->image_path))
            <div style="margin-top:8px; display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
              <a href="{{ asset('storage/'.$course->image_path) }}" download class="btn btn-outline">Download current image</a>
              <a href="{{ asset('storage/'.$course->image_path) }}" target="_blank" class="btn btn-ghost">Open full size →</a>
            </div>
          @endif
        </div>
      </div>

      <div class="actions">
        <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Update Course' : 'Add Course' }}</button>
        @if($isEdit)
          <a href="{{ route('courses.create') }}" class="btn btn-outline">Cancel edit</a>
        @endif
        <a href="{{ route('courses.index') }}" class="btn btn-ghost" style="margin-left:auto">View courses list →</a>
      </div>
    </form>
  </div>
</div>

<script>
  // Simple live preview for image upload
  (function(){
    const input = document.getElementById('image');
    const preview = document.getElementById('preview');
    if(!input) return;
    input.addEventListener('change', function(){
      const file = this.files && this.files[0];
      if(!file) return;
      const url = URL.createObjectURL(file);
      if(preview){ preview.src = url; preview.style.display='block'; }
    });
  })();
</script>
@endsection
