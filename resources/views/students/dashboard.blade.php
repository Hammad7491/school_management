@extends('students.layouts.app')

@section('content')
@php
  use Illuminate\Support\Str;

  $className  = $student->schoolClass->name ?? null;
  $courseName = $student->course->name ?? null;

  $homeworksCount = $homeworksCount ?? (function() use ($student){
      if(!$student->class_id && !$student->course_id) return 0;
      return \App\Models\Homework::where(function($q) use ($student){
          if($student->class_id)  $q->orWhere('class_id',  $student->class_id);
          if($student->course_id) $q->orWhere('course_id', $student->course_id);
      })->count();
  })();

  $hasMonthlyReport = $hasMonthlyReport ?? \App\Models\MonthlyReport::where('reg_no', $student->reg_no)->exists();

  $studentPhoto = $student->profile_image_url;

  $initials = collect(explode(' ', trim($student->name)))
      ->filter()
      ->map(fn($p) => Str::upper(Str::substr($p, 0, 1)))
      ->take(2)
      ->implode('') ?: 'S';
@endphp

<style>
  :root{
    --bg:#f7f9ff; --ink:#0b1020; --muted:#6b7280; --card:#ffffff; --stroke:rgba(15,23,42,.10);
    --brand1:#6a7bff; --brand2:#22d3ee; --ring:rgba(106,123,255,.28);
    --ok:#10b981; --warn:#f59e0b; --danger:#e11d48; --radius:18px;
  }
  @media (prefers-color-scheme: dark){
    :root{
      --bg:#0b1020; --ink:#e6e9f5; --muted:#9aa3ba;
      --card:#0f1830; --stroke:rgba(255,255,255,.12); --ring:rgba(106,123,255,.45);
    }
  }

  .page{
    min-height:100dvh;
    background:
      radial-gradient(800px 400px at -10% 0%, rgba(106,123,255,.12), transparent 60%),
      radial-gradient(700px 500px at 110% -10%, rgba(34,211,238,.12), transparent 60%),
      var(--bg);
    color:var(--ink);
  }
  .wrap{ max-width:1100px; margin:0 auto; padding:20px 12px 72px; }

  .cardx{
    background:var(--card);
    border:1px solid var(--stroke);
    border-radius:var(--radius);
    box-shadow:0 18px 45px rgba(2,6,23,.08);
    overflow:hidden;
  }
  .cardx .body{ padding:18px; }
  .cardx .footer{
    padding:12px 16px;
    border-top:1px solid var(--stroke);
    display:flex;
    flex-wrap:wrap;
    gap:10px;
  }

  /* ✅ NEW HERO GRID (THIS IS THE MAIN FIX) */
  .hero-grid{
    display:grid;
    grid-template-columns: 120px 1fr auto;  /* photo | text | pill */
    gap:16px;
    align-items:center;
  }
  @media (max-width: 760px){
    .hero-grid{
      grid-template-columns: 90px 1fr;
      grid-template-areas:
        "photo text"
        "pill  pill";
      align-items:start;
    }
    .hero-pill{ grid-area:pill; justify-self:start; margin-top:6px; }
    .hero-photo{ grid-area:photo; }
    .hero-text{ grid-area:text; }
  }

  /* ✅ BIG PHOTO */
  .hero-photo{
    width:110px;
    height:110px;
    border-radius:18px; /* square with rounded corners (premium) */
    overflow:hidden;
    border:2px solid rgba(106,123,255,.22);
    background:linear-gradient(135deg, rgba(106,123,255,.18), rgba(34,211,238,.18));
    box-shadow:0 16px 38px rgba(2,6,23,.16);
    position:relative;
    flex:0 0 auto;
  }
  .hero-photo img{
    width:100%;
    height:100%;
    object-fit:cover;
    display:block;
  }
  .hero-photo .fallback{
    width:100%;
    height:100%;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:900;
    font-size:26px;
    color:#fff;
  }

  /* ✅ SMALLER, CLEANER WELCOME */
  .hero-text .welcome{
    font-size: clamp(20px, 2.6vw, 30px);
    font-weight:900;
    letter-spacing:-0.02em;
    margin:0;
    line-height:1.1;
  }
  .hero-text .name{
    margin-top:6px;
    font-size: clamp(26px, 3.2vw, 40px); /* controlled (NOT TOO BIG) */
    font-weight:900;
    line-height:1.05;
    background:linear-gradient(90deg,var(--brand1),var(--brand2));
    -webkit-background-clip:text;
    background-clip:text;
    color:transparent;
  }
  .hero-text .sub{
    color:var(--muted);
    margin-top:6px;
    font-size:14px;
    font-weight:700;
  }

  .hero-pill{
    display:inline-flex;
    align-items:center;
    gap:.5rem;
    padding:8px 12px;
    border-radius:999px;
    border:1px solid var(--stroke);
    font-weight:900;
    font-size:12px;
    color:var(--muted);
    white-space:nowrap;
    justify-self:end;
  }

  .btn{
    border:0;
    border-radius:12px;
    padding:10px 14px;
    font-weight:900;
    cursor:pointer;
    font-size:14px;
    text-decoration:none;
    display:inline-flex;
    justify-content:center;
    align-items:center;
  }
  .btn-outline{
    background:transparent;
    color:var(--ink);
    border:1px solid var(--stroke);
  }
  .btn-primary{
    color:#fff;
    background:linear-gradient(90deg,var(--brand1),var(--brand2));
    box-shadow:0 10px 22px rgba(106,123,255,.35);
  }
  .btn-cta{
    text-align:center;
    flex:1 1 calc(25% - 8px);
    min-width:140px;
  }
  @media (max-width:991.98px){ .btn-cta{ flex:1 1 calc(33.333% - 8px); } }
  @media (max-width:767.98px){ .btn-cta{ flex:1 1 calc(50% - 8px); } }
  @media (max-width:575.98px){
    .cardx .footer{ flex-direction:column; }
    .btn-cta{ width:100%; }
    .hero-photo{ width:86px; height:86px; border-radius:16px; }
  }

  .cards{
    display:grid;
    gap:14px;
    grid-template-columns:1fr;
    margin-top:14px;
  }

  .label{ font-weight:800; color:var(--muted); margin-bottom:6px; font-size:13px; }
  .metric{ font-size:clamp(20px,4vw,26px); font-weight:900; }

  .badge{
    font-weight:800;
    padding:.35rem .7rem;
    border-radius:999px;
    font-size:12px;
  }
  .bg-ok{ background:rgba(16,185,129,.12); color:#065f46; }
  .bg-warn{ background:rgba(245,158,11,.12); color:#7c2d12; }
  .bg-danger{ background:rgba(225,29,72,.12); color:#7f1d1d; }
  @media (prefers-color-scheme: dark){
    .bg-ok{ color:#bbf7d0; }
    .bg-warn{ color:#fde68a; }
    .bg-danger{ color:#fecdd3; }
  }
</style>

<div class="page">
  <div class="wrap">

    <div class="cardx">
      <div class="body">
        <div class="hero-grid">

          {{-- ✅ BIG SQUARE PHOTO --}}
          <div class="hero-photo" title="Profile Photo">
            <img id="studentAvatarImg" src="{{ $studentPhoto }}" alt="{{ $student->name }}" style="display:none;">
            <div id="studentAvatarFallback" class="fallback">{{ $initials }}</div>
          </div>

          {{-- ✅ TEXT --}}
          <div class="hero-text">
            <div class="welcome">Welcome,</div>
            <div class="name">{{ Str::headline($student->name) }}</div>
            <div class="sub">Reg #: <strong>{{ $student->reg_no }}</strong></div>
          </div>

          {{-- ✅ PILL --}}
          <div class="hero-pill">Student Dashboard</div>

        </div>
      </div>

      <div class="footer" aria-label="Quick actions">
        <a class="btn btn-outline btn-cta" href="{{ route('student.homeworks') }}">View Homework</a>
        <a class="btn btn-outline btn-cta" href="{{ route('student.exams') }}">View Exams</a>
        <a class="btn btn-outline btn-cta" href="{{ route('student.monthlyreports') }}">Monthly Reports</a>
        <a class="btn btn-primary btn-cta" href="{{ route('student.results') }}">Check Results</a>
      </div>
    </div>

    <div class="cards">
      @if($className)
        <div class="cardx"><div class="body">
          <div class="label">Class</div>
          <div class="metric">{{ $className }}</div>
        </div></div>
      @endif

      @if($courseName)
        <div class="cardx"><div class="body">
          <div class="label">Course</div>
          <div class="metric">{{ $courseName }}</div>
        </div></div>
      @endif

      <div class="cardx"><div class="body">
        <div class="label">Status</div>
        @if ($student->status === 1)
          <span class="badge bg-ok">Approved</span>
        @elseif ($student->status === 0)
          <span class="badge bg-danger">Rejected</span>
        @else
          <span class="badge bg-warn">Pending</span>
        @endif
        <div class="sub" style="margin-top:8px;">Use the quick actions above to navigate.</div>
      </div></div>

      <div class="cardx"><div class="body">
        <div class="label">Homework Assigned</div>
        <div class="metric">{{ $homeworksCount }}</div>
      </div></div>

      <div class="cardx"><div class="body">
        <div class="label">Monthly Report</div>
        @if($hasMonthlyReport)
          <div class="metric">Available</div>
          <div class="sub" style="margin-top:4px;">A report has been uploaded for your REG #</div>
        @else
          <div class="metric">Not Uploaded</div>
          <div class="sub" style="margin-top:4px;">No report found for your REG # yet</div>
        @endif
      </div></div>
    </div>

  </div>
</div>

<script>
(function(){
  const img = document.getElementById('studentAvatarImg');
  const fb  = document.getElementById('studentAvatarFallback');
  if(!img) return;

  img.onload = function(){
    img.style.display = 'block';
    if (fb) fb.style.display = 'none';
  };

  img.onerror = function(){
    img.style.display = 'none';
    if (fb) fb.style.display = 'flex';
  };

  if (img.complete && img.naturalWidth > 0) img.onload();
  else if (img.complete) img.onerror();
})();
</script>

@endsection
