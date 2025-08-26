@extends('layouts.app')

@section('content')
<style>
  /* —— Modern form styling to match your updated UI —— */
  :root{
    --bg:#f7f9ff; --ink:#0b1020; --muted:#6b7280; --card:#ffffff; --stroke:rgba(15,23,42,.10);
    --brand1:#6a7bff; --brand2:#22d3ee; --ring:rgba(106,123,255,.28); --danger:#e11d48; --radius:18px;
    --ok:#10b981; --warn:#f59e0b;
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
  .topbar{ display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; }
  .btn-link{ color:#4f46e5; text-decoration:none; font-weight:800; }
  .btn-link:hover{ text-decoration:underline; }

  .cardx{ background:var(--card); border:1px solid var(--stroke); border-radius:var(--radius); box-shadow:0 20px 55px rgba(2,6,23,.08); }
  .cardx .body{ padding:22px; }

  .grid{ display:grid; gap:16px; grid-template-columns:1fr; }
  @media (min-width:760px){ .grid-2{ grid-template-columns:1fr 1fr; } }

  .label{ font-weight:800; margin-bottom:8px; display:flex; align-items:center; gap:.35rem; }
  .req{ color:var(--danger); font-weight:900; }

  .input, .select, .textarea{
    width:100%; padding:14px; border-radius:14px; border:1px solid var(--stroke); background:#fff; color:inherit;
    outline:none; transition:.18s border-color, .18s box-shadow;
  }
  .textarea{ min-height:96px; resize:vertical; }
  .input:focus, .select:focus, .textarea:focus{ border-color:var(--brand1); box-shadow:0 0 0 6px var(--ring); }
  @media (prefers-color-scheme: dark){ .input,.select,.textarea{ background:rgba(255,255,255,.02); } }

  .form-help{ color:var(--muted); font-size:12px; margin-top:6px; }

  .uploader{
    position:relative; border:1px dashed var(--stroke); border-radius:14px; background:rgba(106,123,255,.05);
    display:flex; gap:14px; align-items:center; padding:14px;
  }
  .uploader:hover{ box-shadow:0 0 0 6px var(--ring); }
  .uploader input[type=file]{ position:absolute; inset:0; opacity:0; cursor:pointer; }
  .file-chip{ display:inline-block; padding:6px 10px; border-radius:999px; border:1px solid var(--stroke); font-weight:800; }
  .file-meta{ color:var(--muted); font-size:12px; }
  .file-name{ margin-top:4px; font-weight:700; }

  .alerts{ display:grid; gap:10px; margin-bottom:12px; }
  .alertx{ padding:12px 14px; border-radius:12px; border:1px solid; }
  .alert-success{ background:#ecfdf5; border-color:#a7f3d0; color:#065f46; }
  .alert-danger{ background:#fef2f2; border-color:#fecaca; color:#7f1d1d; }
  .alert-warn{ background:#fffbeb; border-color:#fde68a; color:#7c2d12; }
  @media (prefers-color-scheme: dark){
    .alert-success{ background:rgba(16,185,129,.14); border-color:rgba(16,185,129,.45); color:#d1fae5; }
    .alert-danger{ background:rgba(225,29,72,.16); border-color:rgba(225,29,72,.45); color:#ffe4e6; }
    .alert-warn{ background:rgba(245,158,11,.16); border-color:rgba(245,158,11,.45); color:#fde68a; }
  }

  .actions{ display:flex; flex-wrap:wrap; gap:12px; align-items:center; margin-top:6px; }
  .btn{ border:0; border-radius:12px; padding:12px 16px; font-weight:900; cursor:pointer; }
  .btn-primary{ color:#fff; background:linear-gradient(90deg,var(--brand1),var(--brand2)); box-shadow:0 12px 28px rgba(106,123,255,.35); }
  .btn-primary:hover{ filter:brightness(1.05); transform:translateY(-1px); }
  .btn-outline{ background:transparent; color:var(--ink); border:1px solid var(--stroke); }
</style>

<div class="page">
  <div class="wrap">
    <div class="topbar">
      <h1 class="title"><span>Results</span> Upload</h1>
      <a href="{{ route('monthlyreports.index') }}" class="btn-link">← Monthly Reports</a>
    </div>

    <div class="alerts">
      @if(session('success'))
        <div class="alertx alert-success">{{ session('success') }}</div>
      @endif
      @if($errors->any())
        <div class="alertx alert-danger">
          <ul class="mb-0" style="margin-left:1rem;">
            @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
          </ul>
        </div>
      @endif
      @if(session('errors_list') && count(session('errors_list')))
        <div class="alertx alert-warn">
          <strong>Row issues:</strong>
          <ul class="mb-0" style="margin-left:1rem;">
            @foreach(session('errors_list') as $msg) <li>{{ $msg }}</li> @endforeach
          </ul>
        </div>
      @endif
    </div>

    <div class="cardx">
      <div class="body">
        <form action="{{ route('admin.results.upload') }}" method="POST" enctype="multipart/form-data" novalidate>
          @csrf

          {{-- Term & Scope --}}
          <div class="grid grid-2">
            <div>
              <label class="label" for="term_id">Term <span class="req">*</span></label>
              <select id="term_id" name="term_id" class="select" required>
                <option value="">— Select Term —</option>
                @foreach($terms as $t)
                  <option value="{{ $t->id }}" {{ old('term_id') == $t->id ? 'selected' : '' }}>
                    {{ $t->name }}
                  </option>
                @endforeach
              </select>
              <div class="form-help">Standard: Mid Term, Second Term, Final Term.</div>
            </div>

            <div>
              <label class="label" for="scope">Scope <span class="req">*</span></label>
              <select name="scope" id="scope" class="select" required>
                <option value="">— Select —</option>
                <option value="class"  {{ old('scope') === 'class' ? 'selected' : '' }}>Class Result Upload</option>
                {{-- <option value="course" {{ old('scope') === 'course' ? 'selected' : '' }}>Course Result Upload</option> --}}
              </select>
              <div class="form-help">Choose whether this CSV is for a whole class or a single course.</div>
            </div>
          </div>

          {{-- Class / Course selects (conditional) --}}
          <div class="grid grid-2" style="margin-top:6px;">
            <div id="class_wrap" style="display:none;">
              <label class="label" for="class_id">Class</label>
              <select id="class_id" name="class_id" class="select">
                <option value="">— Select Class —</option>
                @foreach($classes as $c)
                  <option value="{{ $c->id }}" {{ old('class_id') == $c->id ? 'selected' : '' }}>
                    {{ $c->name }}
                  </option>
                @endforeach
              </select>
              <div class="form-help">One CSV = one class (for selected term).</div>
            </div>

            <div id="course_wrap" style="display:none;">
              <label class="label" for="course_id">Course</label>
              <select id="course_id" name="course_id" class="select">
                {{-- <option value="">— Select Course —</option> --}}
                @foreach($courses as $c)
                  <option value="{{ $c->id }}" {{ old('course_id') == $c->id ? 'selected' : '' }}>
                    {{ $c->name }}
                  </option>
                @endforeach
              </select>
              <div class="form-help">One CSV = one course (for selected term).</div>
            </div>
          </div>

          {{-- CSV uploader --}}
          <div style="margin-top:8px;">
            <label class="label" for="csv">CSV File <span class="req">*</span></label>
            <div class="uploader">
              <div>
                <span class="file-chip">CSV</span>
                <div class="file-meta">Click to choose a .csv file (max ~5MB)</div>
                <div id="fileName" class="file-name"></div>
              </div>
              <input id="csv" type="file" name="csv" accept=".csv,text/csv" required>
            </div>
            <div class="form-help">
              Required headers:
              <code>reg_no</code>, <code>subject</code>, <code>total_marks</code>, <code>obtained_marks</code>.
              Optional:
              <code>exam_date</code>, <code>attendance_total</code>, <code>attendance_present</code>, <code>remarks</code>.
            </div>
          </div>

          <div class="actions">
            <button class="btn btn-primary" type="submit">Upload</button>
            <a href="{{ url()->previous() }}" class="btn btn-outline">Cancel</a>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>

<script>
  // Scope toggle: show/hide + set required appropriately
  document.addEventListener('DOMContentLoaded', function(){
    const scope      = document.getElementById('scope');
    const classWrap  = document.getElementById('class_wrap');
    const courseWrap = document.getElementById('course_wrap');
    const classSel   = document.getElementById('class_id');
    const courseSel  = document.getElementById('course_id');

    function toggle(){
      const v = scope.value;
      const isClass  = v === 'class';
      const isCourse = v === 'course';

      classWrap.style.display  = isClass  ? 'block' : 'none';
      courseWrap.style.display = isCourse ? 'block' : 'none';

      if (classSel)  classSel.required  = isClass;
      if (courseSel) courseSel.required = isCourse;
    }

    toggle();
    scope.addEventListener('change', toggle);
  });

  // CSV filename preview
  (function(){
    const input = document.getElementById('csv');
    const nameEl = document.getElementById('fileName');
    if(!input) return;
    input.addEventListener('change', () => {
      const f = input.files && input.files[0];
      nameEl.textContent = f ? f.name : '';
    });
  })();
</script>
@endsection
