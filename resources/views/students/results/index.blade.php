@extends('students.layouts.app')

@section('content')
@php
  $hasClass  = (bool)$student->class_id;
  $hasCourse = (bool)$student->course_id;

  // Pre-compute overall percentages for meters
  $classPct = null;
  if (!empty($totals['class']['total'])) {
      $classPct = round(($totals['class']['obtained'] / max(1, $totals['class']['total'])) * 100, 2);
  }
  $coursePct = null;
  if (!empty($totals['course']['total'])) {
      $coursePct = round(($totals['course']['obtained'] / max(1, $totals['course']['total'])) * 100, 2);
  }
@endphp

<style>
  :root{
    --bg:#f7f9ff; --ink:#0b1020; --muted:#6b7280; --card:#ffffff; --stroke:rgba(15,23,42,.10);
    --brand1:#6a7bff; --brand2:#22d3ee; --ring:rgba(106,123,255,.28); --radius:18px;
    --ok:#10b981; --warn:#f59e0b; --danger:#e11d48;
  }
  @media (prefers-color-scheme: dark){
    :root{ --bg:#0b1020; --ink:#e6e9f5; --muted:#9aa3ba; --card:#0f1830; --stroke:rgba(255,255,255,.12); --ring:rgba(106,123,255,.45); }
  }

  .page{
    min-height:100dvh;
    background:
      radial-gradient(800px 400px at -10% 0%, rgba(106,123,255,.12), transparent 60%),
      radial-gradient(700px 500px at 110% -10%, rgba(34,211,238,.12), transparent 60%),
      var(--bg);
    color:var(--ink);
  }
  .wrap{ max-width:1100px; margin:0 auto; padding:24px 12px 72px; }

  .title{ font-size: clamp(26px,5vw,44px); font-weight:900; margin:0; line-height:1.06; }
  .title span{ background:linear-gradient(90deg,var(--brand1),var(--brand2)); -webkit-background-clip:text; background-clip:text; color:transparent; }
  .subtle{ color:var(--muted); }

  .cardx{ background:var(--card); border:1px solid var(--stroke); border-radius:var(--radius); box-shadow:0 18px 45px rgba(2,6,23,.08); }
  .cardx .body{ padding:16px; }
  .cardx .footer{ padding:12px 16px; border-top:1px solid var(--stroke); }

  .bar{ display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; margin-bottom:12px; }

  .chips{ display:flex; gap:8px; align-items:center; flex-wrap:wrap; }
  .chip{ display:inline-flex; align-items:center; gap:.45rem; padding:8px 10px; border-radius:999px; border:1px solid var(--stroke); background:var(--card); font-weight:800; color:var(--ink); }
  .chip .muted{ color:var(--muted); font-weight:700; }

  /* Form (Term filter) */
  .form-grid{ display:grid; gap:10px; grid-template-columns: 1fr; }
  @media (min-width: 560px){ .form-grid{ grid-template-columns: 1fr auto; } }
  .form-label{ font-weight:800; margin-bottom:6px; }
  .control{ border:1px solid var(--stroke); border-radius:12px; padding:12px 14px; background:var(--card); color:var(--ink); width:100%; }
  .control:focus{ outline:none; box-shadow:0 0 0 4px var(--ring); border-color:transparent; }
  .btn{ border:0; border-radius:12px; padding:12px 16px; font-weight:900; cursor:pointer; }
  .btn-primary{ color:#fff; background:linear-gradient(90deg,var(--brand1),var(--brand2)); box-shadow:0 10px 22px rgba(106,123,255,.35); }
  .btn-primary:hover{ filter:brightness(1.05); transform: translateY(-1px); }

  /* Results header row inside each card */
  .card-head{ display:flex; align-items:center; justify-content:space-between; gap:10px; flex-wrap:wrap; padding:12px 16px; border-bottom:1px solid var(--stroke); font-weight:800; }
  .muted{ color:var(--muted); }
  .kpis{ display:flex; gap:14px; align-items:center; flex-wrap:wrap; }
  .kpis .k{ display:flex; gap:6px; align-items:center; }
  .k .label{ color:var(--muted); font-weight:800; }
  .k .val{ font-weight:900; }

  /* Percentage meter */
  .meter{ position:relative; height:10px; border-radius:999px; background:rgba(106,123,255,.15); overflow:hidden; }
  .meter > i{ position:absolute; inset:0; width:0%; display:block; background:linear-gradient(90deg,var(--brand1),var(--brand2)); }
  .meter-wrap{ display:grid; gap:8px; grid-template-columns: 1fr auto; align-items:center; }
  .pct-badge{ font-weight:900; padding:4px 8px; border-radius:999px; border:1px solid var(--stroke); background:var(--card); }

  /* Data table */
  .data-table{ width:100%; border-collapse:separate; border-spacing:0; }
  .data-table th, .data-table td{ padding:12px 16px; border-bottom:1px solid var(--stroke); vertical-align:top; }
  .data-table thead th{
    background:linear-gradient(180deg, rgba(106,123,255,.06), rgba(34,211,238,.06));
    font-weight:800; text-align:left;
  }
  .data-table tbody tr:hover{ background: rgba(106,123,255,.06); }

  /* Attendance footer text */
  .att{ color:var(--muted); font-weight:700; }

  /* Mobile: table becomes stacked cards */
  @media (max-width: 700px){
    .data-table thead{ display:none; }
    .data-table, .data-table tbody, .data-table tr, .data-table td{ display:block; width:100%; }
    .data-table tr{
      margin:0 12px 12px; border:1px solid var(--stroke); background:var(--card); border-radius:14px;
      box-shadow:0 14px 26px rgba(2,6,23,.06);
    }
    .data-table td{
      display:flex; gap:12px; justify-content:space-between; padding:12px 14px; border-bottom:1px solid var(--stroke);
    }
    .data-table td:last-child{ border-bottom:none; }
    .data-table td::before{
      content:attr(data-label); font-weight:800; color:var(--muted); padding-right:10px; text-align:left;
      flex:0 0 120px; max-width:50%;
    }
  }

  /* Alerts */
  .alertx{ padding:12px 14px; border-radius:12px; border:1px solid; }
  .info{ background:#eff6ff; border-color:#bfdbfe; color:#1e40af; }
  @media (prefers-color-scheme: dark){ .info{ background:rgba(59,130,246,.16); border-color:rgba(59,130,246,.45); color:#c7d2fe; } }
  .warn{ background:#fff7ed; border-color:#fed7aa; color:#7c2d12; }
  @media (prefers-color-scheme: dark){ .warn{ background:rgba(245,158,11,.16); border-color:rgba(245,158,11,.45); color:#fde68a; } }
</style>

<div class="page">
  <div class="wrap">

    {{-- Page header --}}
    <div class="bar">
      <div>
        <h1 class="title">Exam <span>Results</span></h1>
        <div class="subtle">Select a term to view your results.</div>
      </div>
      <div class="chips">
        <div class="chip"><span class="muted">Student:</span> {{ $student->name }}</div>
        <div class="chip"><span class="muted">Reg #:</span> {{ $student->reg_no }}</div>
        @if($hasClass)
          <div class="chip"><span class="muted">Class:</span> {{ $student->schoolClass?->name }}</div>
        @endif
        @if($hasCourse)
          <div class="chip"><span class="muted">Course:</span> {{ $student->course?->name }}</div>
        @endif
      </div>
    </div>

    {{-- Term filter --}}
    <form method="GET" class="cardx" style="margin-bottom:12px;">
      <div class="body">
        <div class="form-grid">
          <div>
            <label class="form-label">Term</label>
            <select name="term_id" class="control">
              <option value="">— Select Term —</option>
              @foreach($terms as $t)
                <option value="{{ $t->id }}" {{ (string)$termId === (string)$t->id ? 'selected' : '' }}>
                  {{ $t->name }}
                </option>
              @endforeach
            </select>
          </div>
          <div style="align-self:end;">
            <button class="btn btn-primary" type="submit" style="width:100%;">Show</button>
          </div>
        </div>
      </div>
    </form>

    @if(!$termId)
      <div class="alertx info" style="margin-bottom:12px;">Please select a term to view your results.</div>
    @else

      {{-- CLASS RESULTS --}}
      @if($hasClass)
      <div class="cardx" style="margin-bottom:14px;">
        <div class="card-head">
          <div>Class Results</div>
          <div class="kpis">
            <div class="k"><span class="label">Total:</span> <span class="val">{{ $totals['class']['total'] }}</span></div>
            <div class="k"><span class="label">Obtained:</span> <span class="val">{{ $totals['class']['obtained'] }}</span></div>
            <div class="k meter-wrap" style="min-width:200px;">
              <div class="meter"><i style="width: {{ $classPct !== null ? min(100,max(0,$classPct)) : 0 }}%;"></i></div>
              <div class="pct-badge">{{ $classPct !== null ? $classPct.'%' : '—' }}</div>
            </div>
          </div>
        </div>

        <div class="body" style="padding:0;">
          <table class="data-table">
            <thead>
              <tr>
                <th>Subject</th>
                <th class="text-end">Total</th>
                <th class="text-end">Obtained</th>
                <th class="text-end">%</th>
              </tr>
            </thead>
            <tbody>
              @forelse($classResults as $r)
                @php
                  $perc = $r->total_marks ? round(($r->obtained_marks/$r->total_marks)*100,2) : null;
                  $subjectLabel = $r->subject->name
                      ?? ($r->subject_name ?? (is_string($r->subject) ? $r->subject : '—'));
                @endphp
                <tr>
                  <td data-label="Subject">{{ $subjectLabel }}</td>
                  <td data-label="Total" class="text-end">{{ $r->total_marks }}</td>
                  <td data-label="Obtained" class="text-end">{{ $r->obtained_marks }}</td>
                  <td data-label="%" class="text-end">{{ $perc !== null ? $perc.'%' : '—' }}</td>
                </tr>
              @empty
                <tr><td colspan="4" class="subtle" style="text-align:center; padding:18px;">No class results for this term.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>

        @if($classAttendance)
          <div class="footer att">
            Attendance: {{ $classAttendance->present_days }}/{{ $classAttendance->total_days }}
            ({{ $classAttendance->percentage ?? '—' }}%)
          </div>
        @endif
      </div>
      @endif

      {{-- COURSE RESULTS --}}
      @if($hasCourse)
      <div class="cardx" style="margin-bottom:14px;">
        <div class="card-head">
          <div>Course Results</div>
          <div class="kpis">
            <div class="k"><span class="label">Total:</span> <span class="val">{{ $totals['course']['total'] }}</span></div>
            <div class="k"><span class="label">Obtained:</span> <span class="val">{{ $totals['course']['obtained'] }}</span></div>
            <div class="k meter-wrap" style="min-width:200px;">
              <div class="meter"><i style="width: {{ $coursePct !== null ? min(100,max(0,$coursePct)) : 0 }}%;"></i></div>
              <div class="pct-badge">{{ $coursePct !== null ? $coursePct.'%' : '—' }}</div>
            </div>
          </div>
        </div>

        <div class="body" style="padding:0;">
          <table class="data-table">
            <thead>
              <tr>
                <th>Subject</th>
                <th class="text-end">Total</th>
                <th class="text-end">Obtained</th>
                <th class="text-end">%</th>
              </tr>
            </thead>
            <tbody>
              @forelse($courseResults as $r)
                @php
                  $perc = $r->total_marks ? round(($r->obtained_marks/$r->total_marks)*100,2) : null;
                  $subjectLabel = $r->subject->name
                      ?? ($r->subject_name ?? (is_string($r->subject) ? $r->subject : '—'));
                @endphp
                <tr>
                  <td data-label="Subject">{{ $subjectLabel }}</td>
                  <td data-label="Total" class="text-end">{{ $r->total_marks }}</td>
                  <td data-label="Obtained" class="text-end">{{ $r->obtained_marks }}</td>
                  <td data-label="%" class="text-end">{{ $perc !== null ? $perc.'%' : '—' }}</td>
                </tr>
              @empty
                <tr><td colspan="4" class="subtle" style="text-align:center; padding:18px;">No course results for this term.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>

        @if($courseAttendance)
          <div class="footer att">
            Attendance: {{ $courseAttendance->present_days }}/{{ $courseAttendance->total_days }}
            ({{ $courseAttendance->percentage ?? '—' }}%)
          </div>
        @endif
      </div>
      @endif

      @if(!$hasClass && !$hasCourse)
        <div class="alertx warn">You are not enrolled in a class or course.</div>
      @endif

    @endif
  </div>
</div>
@endsection
