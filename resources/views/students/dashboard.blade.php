@extends('students.layouts.app')

@section('content')
@php
  $className  = $student->schoolClass->name ?? null;
  $courseName = $student->course->name ?? null;

  // Safe inline fallbacks (use controller values if you already pass them)
  $homeworksCount = $homeworksCount ?? (function() use ($student){
      if(!$student->class_id && !$student->course_id) return 0;
      return \App\Models\Homework::where(function($q) use ($student){
          if($student->class_id)  $q->orWhere('class_id',  $student->class_id);
          if($student->course_id) $q->orWhere('course_id', $student->course_id);
      })->count();
  })();
  $hasMonthlyReport = $hasMonthlyReport ?? \App\Models\MonthlyReport::where('reg_no', $student->reg_no)->exists();
@endphp

<style>
  :root{
    --bg:#f7f9ff; --ink:#0b1020; --muted:#6b7280; --card:#ffffff; --stroke:rgba(15,23,42,.10);
    --brand1:#6a7bff; --brand2:#22d3ee; --ring:rgba(106,123,255,.28);
    --ok:#10b981; --warn:#f59e0b; --danger:#e11d48; --radius:18px;
  }
  @media (prefers-color-scheme: dark){
    :root{ --bg:#0b1020; --ink:#e6e9f5; --muted:#9aa3ba; --card:#0f1830; --stroke:rgba(255,255,255,.12); --ring:rgba(106,123,255,.45); }
  }
  .page{ min-height:100dvh; background:
    radial-gradient(800px 400px at -10% 0%, rgba(106,123,255,.12), transparent 60%),
    radial-gradient(700px 500px at 110% -10%, rgba(34,211,238,.12), transparent 60%),
    var(--bg); color:var(--ink); }
  .wrap{ max-width:1100px; margin:0 auto; padding:28px 14px 72px; }
  .cardx{ background:var(--card); border:1px solid var(--stroke); border-radius:var(--radius); box-shadow:0 18px 45px rgba(2,6,23,.08); }
  .cardx .body{ padding:16px; }
  .cardx .footer{ padding:12px 16px; border-top:1px solid var(--stroke); display:flex; flex-wrap:wrap; gap:8px; }

  .title{ font-size:clamp(26px,5vw,44px); font-weight:900; line-height:1.05; margin:4px 0 8px; }
  .title span{ background:linear-gradient(90deg,var(--brand1),var(--brand2)); -webkit-background-clip:text; background-clip:text; color:transparent; }
  .pill{ display:inline-flex; align-items:center; gap:.5rem; padding:6px 10px; border-radius:999px; border:1px solid var(--stroke); font-weight:800; font-size:12px; color:var(--muted); }
  .sub{ color:var(--muted); }

  .btn{ border:0; border-radius:12px; padding:10px 14px; font-weight:900; cursor:pointer; }
  .btn-outline{ background:transparent; color:var(--ink); border:1px solid var(--stroke); }
  .btn-primary{ color:#fff; background:linear-gradient(90deg,var(--brand1),var(--brand2)); box-shadow:0 10px 22px rgba(106,123,255,.35); }
  .btn-primary:hover{ filter:brightness(1.05); transform:translateY(-1px); }

  .cards{ display:grid; gap:14px; grid-template-columns:1fr; }
  @media (min-width:768px){ .cards{ grid-template-columns:repeat(12, 1fr); } }

  .label{ font-weight:800; color:var(--muted); margin-bottom:6px; }
  .metric{ font-size:clamp(22px,4.2vw,28px); font-weight:900; }
  .stat{ display:flex; align-items:center; justify-content:space-between; gap:10px; }
  .badge{ font-weight:800; padding:.35rem .6rem; border-radius:999px; }
  .bg-ok{ background:rgba(16,185,129,.12); color:#065f46; }
  .bg-warn{ background:rgba(245,158,11,.12); color:#7c2d12; }
  .bg-danger{ background:rgba(225,29,72,.12); color:#7f1d1d; }
  @media (prefers-color-scheme: dark){
    .bg-ok{ color:#bbf7d0; } .bg-warn{ color:#fde68a; } .bg-danger{ color:#fecdd3; }
  }
</style>

<div class="page">
  <div class="wrap">

    {{-- Header / welcome --}}
    <div class="cardx" style="margin-bottom:14px;">
      <div class="body" style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
        <div>
          <h1 class="title">Welcome, <span>{{ Str::headline($student->name) }}</span></h1>
          <div class="sub">Reg #: <strong>{{ $student->reg_no }}</strong></div>
        </div>
        <div class="pill">Student Dashboard</div>
      </div>

      {{-- Single quick-actions row (the only CTAs) --}}
      <div class="footer" aria-label="Quick actions">
        <a class="btn btn-outline btn-sm" href="{{ route('student.homeworks') }}">View Homework</a>
        <a class="btn btn-outline btn-sm" href="{{ route('student.exams') }}">View Exams</a>
        <a class="btn btn-outline btn-sm" href="{{ route('student.monthlyreports') }}">Monthly Reports</a>
        <a class="btn btn-primary btn-sm" href="{{ route('student.results') }}">Check Results</a>
      </div>
    </div>

    <div class="cards">
      {{-- Class (only if enrolled) --}}
      @if($className)
      <div class="cardx" style="grid-column: span 6;">
        <div class="body">
          <div class="label">Class</div>
          <div class="metric">{{ $className }}</div>
        </div>
      </div>
      @endif

      {{-- Course (only if enrolled) --}}
      @if($courseName)
      <div class="cardx" style="grid-column: span 6;">
        <div class="body">
          <div class="label">Course</div>
          <div class="metric">{{ $courseName }}</div>
        </div>
      </div>
      @endif

      {{-- Status (no extra buttons here) --}}
      <div class="cardx" style="grid-column: span 12;">
        <div class="body stat">
          <div>
            <div class="label">Status</div>
            @if ($student->status === 1)
              <span class="badge bg-ok">Approved</span>
            @elseif ($student->status === 0)
              <span class="badge bg-danger">Rejected</span>
            @else
              <span class="badge bg-warn">Pending</span>
            @endif
          </div>
          <div class="sub">Use the quick actions above to navigate.</div>
        </div>
      </div>

      {{-- Homework metric (information only, no button – avoids duplication) --}}
      <div class="cardx" style="grid-column: span 6;">
        <div class="body">
          <div class="label">Homework Assigned</div>
          <div class="metric">{{ $homeworksCount }}</div>
          <div class="sub" style="margin-top:4px;">
            for your {{ $className && $courseName ? 'class/course' : ($className ? 'class' : ($courseName ? 'course' : 'profile')) }}
          </div>
        </div>
      </div>

      {{-- Monthly report status (information only, no button) --}}
      <div class="cardx" style="grid-column: span 6;">
        <div class="body">
          <div class="label">Monthly Report</div>
          @if($hasMonthlyReport)
            <div class="metric">Available</div>
            <div class="sub" style="margin-top:4px;">A report has been uploaded for your REG #</div>
          @else
            <div class="metric">Not Uploaded</div>
            <div class="sub" style="margin-top:4px;">No report found for your REG # yet</div>
          @endif
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
