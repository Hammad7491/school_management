@extends('layouts.app')

@section('content')
@php
  $isEdit = isset($exam);
  $exam   = $exam ?? null;
@endphp

<style>
  /* ——— Premium-simple styling to match the rest ——— */
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
  .topbar{ display:flex; gap:12px; align-items:center; justify-content:space-between; flex-wrap:wrap; }
  .btn-link{ color:#4f46e5; text-decoration:none; font-weight:800; }
  .btn-link:hover{ text-decoration:underline; }

  .cardx{ background:var(--card); border:1px solid var(--stroke); border-radius:var(--radius); box-shadow:0 20px 55px rgba(2,6,23,.08); }
  .cardx .body{ padding:22px; }

  .grid{ display:grid; gap:16px; grid-template-columns:1fr; }
  @media (min-width:760px){ .grid-2{ grid-template-columns:1fr 1fr; } }

  .label{ font-weight:800; margin-bottom:8px; display:flex; align-items:center; gap:.35rem; }

  .input, .select, .textarea{
    width:100%; padding:14px; border-radius:14px; border:1px solid var(--stroke); background:#fff; color:inherit;
    outline:none; transition:.18s border-color, .18s box-shadow;
  }
  .textarea{ min-height:120px; resize:vertical; }
  .input:focus, .select:focus, .textarea:focus{ border-color:var(--brand1); box-shadow:0 0 0 6px var(--ring); }

  .check-row{ display:flex; gap:10px; align-items:center; margin-bottom:8px; }

  .uploader{ position:relative; border:1px dashed var(--stroke); border-radius:14px; background:rgba(106,123,255,.05);
    display:flex; gap:14px; align-items:center; padding:14px; }
  .uploader:hover{ box-shadow:0 0 0 6px var(--ring); }
  .uploader input[type=file]{ position:absolute; inset:0; opacity:0; cursor:pointer; }
  .thumb{ width:76px; height:76px; border-radius:12px; object-fit:cover; border:1px solid var(--stroke); background:#fff; display:none; }
  .file-meta{ color:var(--muted); font-size:12px; }
  .file-chip{ display:inline-block; padding:6px 10px; border-radius:999px; border:1px solid var(--stroke); font-weight:800; }

  .actions{ display:flex; flex-wrap:wrap; gap:12px; align-items:center; margin-top:12px; }
  .btn{ border:0; border-radius:12px; padding:12px 16px; font-weight:900; cursor:pointer; }
  .btn-primary{ color:#fff; background:linear-gradient(90deg,var(--brand1),var(--brand2)); box-shadow:0 12px 28px rgba(106,123,255,.35); }
  .btn-primary:hover{ filter:brightness(1.05); transform:translateY(-1px); }
  .btn-outline{ background:transparent; color:var(--ink); border:1px solid var(--stroke); }

  .alert-danger{ background:#fef2f2; border:1px solid #fecaca; color:#7f1d1d; border-radius:12px; padding:12px 14px; margin-bottom:14px; }
  @media (prefers-color-scheme: dark){
    .input, .select, .textarea{ background:rgba(255,255,255,.02); }
    .thumb{ background:transparent; }
    .alert-danger{ background:rgba(225,29,72,.16); border-color:rgba(225,29,72,.45); color:#ffe4e6; }
  }
</style>

<div class="page">
  <div class="wrap">
    <div class="topbar">
      <h1 class="title">{{ $isEdit ? 'Edit' : 'Add' }} <span>Exam</span></h1>
      <a href="{{ route('exams.index') }}" class="btn-link">← Back to List</a>
    </div>

    @if ($errors->any())
      <div class="alert-danger" role="alert">
        <ul style="margin:0 0 0 1rem;">
          @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ $isEdit ? route('exams.update', $exam?->id) : route('exams.store') }}"
          method="POST" class="cardx" enctype="multipart/form-data" novalidate>
      @csrf @if($isEdit) @method('PUT') @endif

      <div class="body">
        {{-- Comment --}}
        <div>
          <label class="label" for="comment">Comment</label>
          <textarea id="comment" name="comment" class="textarea" rows="3">{{ old('comment', $exam?->comment ?? '') }}</textarea>
        </div>

        {{-- Assignments --}}
        @php
          $addClass  = old('add_class',  $exam?->class_id  !== null);
          $addCourse = old('add_course', $exam?->course_id !== null);
        @endphp
        <div class="grid grid-2">
          <div>
            <div class="check-row">
              <input class="form-check-input" type="checkbox" id="add_class" name="add_class" value="1" {{ $addClass ? 'checked' : '' }}>
              <label class="label" for="add_class" style="margin:0;">Assign to Class</label>
            </div>
            <select name="class_id" id="class_id" class="select" style="{{ $addClass ? '' : 'display:none;' }}">
              <option value="">— Select Class —</option>
              @foreach($classes as $c)
                <option value="{{ $c->id }}" {{ old('class_id', $exam?->class_id) == $c->id ? 'selected' : '' }}>
                  {{ $c->name }}
                </option>
              @endforeach
            </select>
          </div>

          <div>
            <div class="check-row">
              <input class="form-check-input" type="checkbox" id="add_course" name="add_course" value="1" {{ $addCourse ? 'checked' : '' }}>
              <label class="label" for="add_course" style="margin:0;">Assign to Course</label>
            </div>
            <select name="course_id" id="course_id" class="select" style="{{ $addCourse ? '' : 'display:none;' }}">
              <option value="">— Select Course —</option>
              @foreach($courses as $co)
                <option value="{{ $co->id }}" {{ old('course_id', $exam?->course_id) == $co->id ? 'selected' : '' }}>
                  {{ $co->name }}
                </option>
              @endforeach
            </select>
          </div>
        </div>

        {{-- File upload --}}
        <div style="margin-top:8px;">
          <label class="label" for="file">Upload (Image/PDF)</label>
          <div class="uploader">
            <img id="fileThumb" class="thumb" alt="">
            <div>
              <strong>Click to choose a file</strong>
              <div class="file-meta">PNG/JPG/WEBP/GIF or PDF, up to ~5MB</div>
              <div id="fileName" class="file-meta" style="margin-top:6px;"></div>
            </div>
            <input id="file" type="file" name="file" accept="image/*,application/pdf">
          </div>

          @if($isEdit && $exam?->file_path)
            @php
              $ext = strtolower(pathinfo($exam->file_path, PATHINFO_EXTENSION));
              $isImage = in_array($ext, ['jpg','jpeg','png','webp','gif']);
              $fileLabel = $exam->file_name ?: basename($exam->file_path);
              $fileUrl   = asset('storage/'.$exam->file_path);
            @endphp
            <div class="file-meta" style="margin-top:8px;">
              Current file:
              <span class="file-chip">{{ strtoupper($ext ?: 'FILE') }}</span>
              <a href="{{ $fileUrl }}" target="_blank" style="margin-left:.5rem;">Open</a>
              • <a href="{{ route('exams.download', $exam?->id) }}">Download</a>
              @if($isImage)
                <div style="margin-top:8px;">
                  <img src="{{ $fileUrl }}" alt="file" style="height:70px;border-radius:10px;border:1px solid var(--stroke);object-fit:cover;">
                </div>
              @endif
            </div>
          @endif
        </div>

        {{-- Actions --}}
        <div class="actions">
          <button class="btn btn-primary" type="submit">{{ $isEdit ? 'Update Exam' : 'Add Exam' }}</button>
          @if($isEdit)
            <a href="{{ route('exams.create') }}" class="btn btn-outline">Cancel edit</a>
          @endif
          <a href="{{ route('exams.index') }}" class="btn-link" style="margin-left:auto">Back to list →</a>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
  // Toggle selects + make them required when visible (robust inline style)
  document.addEventListener('DOMContentLoaded', () => {
    const pairs = [
      {chk:'#add_class',  sel:'#class_id'},
      {chk:'#add_course', sel:'#course_id'}
    ];
    const toggle = (c, s) => {
      if(!c || !s) return;
      s.style.display = c.checked ? 'block' : 'none';
      s.required = c.checked;
    };
    pairs.forEach(({chk, sel}) => {
      const c = document.querySelector(chk);
      const s = document.querySelector(sel);
      toggle(c, s);
      c?.addEventListener('change', () => toggle(c, s));
    });
  });

  // File preview: image thumb or file name for PDF
  (function(){
    const input = document.getElementById('file');
    const thumb = document.getElementById('fileThumb');
    const nameEl = document.getElementById('fileName');
    if(!input) return;

    input.addEventListener('change', () => {
      const f = input.files && input.files[0];
      if(!f) return;
      const isImg = /^image\//.test(f.type);
      nameEl.textContent = f.name;
      if(isImg){
        const url = URL.createObjectURL(f);
        thumb.src = url;
        thumb.style.display = 'block';
      }else{
        thumb.style.display = 'none';
      }
    });
  })();
</script>
@endsection
