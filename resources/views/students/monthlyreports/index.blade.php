@extends('students.layouts.app')

@section('content')
@php
  $total = (is_object($reports) && method_exists($reports,'total'))
            ? $reports->total()
            : (is_countable($reports) ? count($reports) : 0);
@endphp

<style>
  :root{
    --bg:#f7f9ff; --ink:#0b1020; --muted:#6b7280; --card:#ffffff; --stroke:rgba(15,23,42,.10);
    --brand1:#6a7bff; --brand2:#22d3ee; --radius:18px; --ring:rgba(106,123,255,.28);
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

  /* Header */
  .title{ font-size: clamp(28px,5.2vw,48px); font-weight:900; line-height:1.05; margin:0; }
  .title span{ background:linear-gradient(90deg,var(--brand1),var(--brand2)); -webkit-background-clip:text; background-clip:text; color:transparent; }
  .subtle{ color:var(--muted); font-weight:600; }

  .bar{ display:flex; align-items:center; gap:12px; justify-content:space-between; flex-wrap:wrap; margin:6px 0 16px; }
  .who{ color:var(--muted); font-weight:700; }

  .search{
    position:relative; min-width:240px; flex:1 1 280px; max-width:520px;
  }
  .search input{
    width:100%; border:1px solid var(--stroke); border-radius:12px; padding:12px 14px 12px 40px;
    background:var(--card); color:var(--ink); outline:none;
  }
  .search input:focus{ box-shadow:0 0 0 4px var(--ring); border-color:transparent; }
  .search svg{ position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--muted); }

  /* Card */
  .cardx{ background:var(--card); border:1px solid var(--stroke); border-radius:var(--radius); box-shadow:0 18px 45px rgba(2,6,23,.08); }
  .cardx .body{ padding:0; }
  .cardx .footer{ padding:12px 16px; border-top:1px solid var(--stroke); }

  /* Data table */
  .data-table{ width:100%; border-collapse:separate; border-spacing:0; }
  .data-table th, .data-table td{ padding:14px 16px; border-bottom:1px solid var(--stroke); vertical-align:top; }
  .data-table thead th{ background:linear-gradient(180deg, rgba(106,123,255,.06), rgba(34,211,238,.06)); font-weight:800; text-align:left; }
  .data-table tbody tr:hover{ background: rgba(106,123,255,.06); }

  .btn-link{
    display:inline-block; padding:8px 12px; border-radius:10px; border:1px solid var(--stroke); text-decoration:none;
    color:var(--ink); font-weight:800; background:transparent;
  }
  .btn-link:hover{ box-shadow:0 0 0 4px var(--ring); }

  /* Empty state */
  .empty{ text-align:center; color:var(--muted); padding:36px 12px; }

  /* --------- Mobile card mode (table ➜ stacked rows) --------- */
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
      flex:0 0 130px; max-width:55%;
    }
    .btn-link{ width:100%; text-align:center; }
  }

  .hidden{ display:none !important; }
</style>

<div class="page">
  <div class="wrap">

    {{-- Header --}}
    <div class="bar">
      <div>
        <h1 class="title">Monthly <span>Reports</span></h1>
        <div class="subtle">{{ $total }} on this page</div>
      </div>
      <div class="who">
        {{ $student->name }} — Reg # <strong>{{ $student->reg_no }}</strong>
      </div>
    </div>

    {{-- Search --}}
    <div class="bar" style="margin-top:-6px;">
      <div class="search">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
          <path d="m21 21-4.3-4.3M16 10.5A5.5 5.5 0 1 1 5 10.5a5.5 5.5 0 0 1 11 0Z"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <input id="repSearch" type="search" placeholder="Search by date, class, course, student or remarks…">
      </div>
    </div>

    {{-- Table/Card --}}
    <div class="cardx">
      <div class="body">
        <table class="data-table">
          <thead>
            <tr>
              <th style="width:9rem;">Report Date</th>
              <th>Student</th>
              <th>Father</th>
              <th>Class</th>
              <th>Course</th>
              <th>Remarks</th>
              <th style="width:10rem;">File</th>
            </tr>
          </thead>
          <tbody id="repBody">
            @forelse($reports as $r)
              @php
                $date = optional($r->report_date)->format('Y-m-d');
                $stu  = $r->student_name;
                $fat  = $r->father_name ?? '—';
                $cls  = $r->schoolClass?->name ?? '—';
                $crs  = $r->course?->name ?? '—';
                $rmk  = $r->remarks ?? '—';
              @endphp
              <tr class="rep-row">
                <td data-label="Report Date" class="fw-semibold">{{ $date }}</td>
                <td data-label="Student">{{ $stu }}</td>
                <td data-label="Father">{{ $fat }}</td>
                <td data-label="Class">{{ $cls }}</td>
                <td data-label="Course">{{ $crs }}</td>
                <td data-label="Remarks" class="text-truncate" style="max-width:480px;">{{ $rmk }}</td>
                <td data-label="File">
                  @if($r->file_path)
                    <a class="btn-link" href="{{ route('monthlyreports.download', $r->id) }}">
                      {{ $r->file_name ?? 'Download' }}
                    </a>
                  @else
                    <span class="subtle">—</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="empty">No monthly reports yet.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- Pagination --}}
      @if(is_object($reports) && method_exists($reports,'hasPages') && $reports->hasPages())
        <div class="footer">{{ $reports->links() }}</div>
      @endif
    </div>

  </div>
</div>

{{-- Real-time filter --}}
<script>
  (function(){
    const q = document.getElementById('repSearch');
    const rows = Array.from(document.querySelectorAll('#repBody .rep-row'));
    function filter(){
      const term = q.value.trim().toLowerCase();
      rows.forEach(row => {
        const hay = row.textContent.toLowerCase(); // date + student + father + class + course + remarks + file text
        row.classList.toggle('hidden', term && !hay.includes(term));
      });
    }
    q.addEventListener('input', filter, { passive:true });
  })();
</script>
@endsection
