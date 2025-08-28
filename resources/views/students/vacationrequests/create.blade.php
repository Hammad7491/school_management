{{-- resources/views/students/vacationrequests/create.blade.php --}}
@extends('students.layouts.app')

@section('content')
@php
  $studentName = $student->name;
  $regNo       = $student->reg_no;
  $className   = $student->schoolClass->name ?? '—';
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
      radial-gradient(820px 420px at -10% 0%, rgba(106,123,255,.12), transparent 60%),
      radial-gradient(720px 520px at 110% -10%, rgba(34,211,238,.12), transparent 60%),
      var(--bg);
    color:var(--ink);
  }
  .wrap{ max-width:900px; margin:0 auto; padding:24px 12px 72px; }

  .title{ font-size: clamp(26px,5.2vw,44px); font-weight:900; line-height:1.06; margin:0 0 6px; }
  .title span{ background:linear-gradient(90deg,var(--brand1),var(--brand2)); -webkit-background-clip:text; background-clip:text; color:transparent; }
  .subtle{ color:var(--muted); }

  .chips{ display:flex; gap:8px; align-items:center; flex-wrap:wrap; margin:8px 0 16px; }
  .chip{ display:inline-flex; align-items:center; gap:.45rem; padding:8px 10px; border-radius:999px; border:1px solid var(--stroke); background:var(--card); font-weight:800; color:var(--ink); }
  .chip .muted{ color:var(--muted); font-weight:700; }

  .cardx{ background:var(--card); border:1px solid var(--stroke); border-radius:var(--radius); box-shadow:0 18px 45px rgba(2,6,23,.08); }
  .cardx .body{ padding:16px; }
  .cardx .footer{ padding:12px 16px; border-top:1px solid var(--stroke); }

  /* Form grid */
  .form-grid{ display:grid; gap:12px; grid-template-columns: 1fr; }
  @media (min-width: 680px){ .form-grid{ grid-template-columns: 1fr 1fr; } }

  .form-item{ display:flex; flex-direction:column; gap:6px; }
  .form-label{ font-weight:800; }
  .control{
    border:1px solid var(--stroke); border-radius:12px; padding:12px 14px; background:var(--card); color:var(--ink); width:100%;
  }
  .control[disabled]{ color:var(--muted); background:rgba(2,6,23,.03); }
  .control:focus{ outline:none; box-shadow:0 0 0 4px var(--ring); border-color:transparent; }

  .span-2{ grid-column: auto; }
  @media (min-width: 680px){ .span-2{ grid-column: span 2; } }

  .btn{ border:0; border-radius:12px; padding:12px 16px; font-weight:900; cursor:pointer; }
  .btn-primary{ color:#fff; background:linear-gradient(90deg,var(--brand1),var(--brand2)); box-shadow:0 10px 22px rgba(106,123,255,.35); }
  .btn-primary:hover{ filter:brightness(1.05); transform: translateY(-1px); }
  .btn-ghost{ background:transparent; color:var(--ink); border:1px solid var(--stroke); }
  .btn-ghost:hover{ box-shadow:0 0 0 4px var(--ring); }

  .row-actions{ display:flex; gap:10px; flex-wrap:wrap; }

  /* Alerts */
  .alertx{ padding:12px 14px; border-radius:12px; border:1px solid; margin-bottom:12px; }
  .danger{ background:#fef2f2; border-color:#fecaca; color:#7f1d1d; }
  @media (prefers-color-scheme: dark){ .danger{ background:rgba(225,29,72,.16); border-color:rgba(225,29,72,.45); color:#fecaca; } }

  /* Date range preview */
  .range{ display:flex; gap:10px; align-items:center; flex-wrap:wrap; }
  .badge{ font-weight:900; padding:6px 10px; border-radius:999px; border:1px solid var(--stroke); background:var(--card); }
  .ok{ color:#065f46; background:rgba(16,185,129,.12); border-color:rgba(16,185,129,.35); }
  .warn{ color:#7c2d12; background:rgba(245,158,11,.12); border-color:rgba(245,158,11,.35); }
</style>

<div class="page">
  <div class="wrap">

    {{-- Header --}}
    <header style="margin-bottom:12px;">
      <h1 class="title">New <span>Leave Request</span></h1>
      <p class="subtle">Fill the form below to request vacation/leave.</p>
      <div class="chips">
        <div class="chip"><span class="muted">Student:</span> {{ $studentName }}</div>
        <div class="chip"><span class="muted">Reg #:</span> {{ $regNo }}</div>
        <div class="chip"><span class="muted">Class:</span> {{ $className }}</div>
      </div>
    </header>

    {{-- Errors --}}
    @if($errors->any())
      <div class="alertx danger">
        <ul style="margin:0; padding-left:18px;">
          @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    {{-- Form --}}
    <div class="cardx">
      <div class="body">
        <form action="{{ route('student.vacation-requests.store') }}" method="POST" class="form-grid" novalidate>
          @csrf

          {{-- Readonly info (always responsive) --}}
          <div class="form-item">
            <label class="form-label">Student</label>
            <input type="text" class="control" value="{{ $studentName }}" disabled>
          </div>

          <div class="form-item">
            <label class="form-label">Reg #</label>
            <input type="text" class="control" value="{{ $regNo }}" disabled>
          </div>

          <div class="form-item span-2">
            <label class="form-label">Class</label>
            <input type="text" class="control" value="{{ $className }}" disabled>
          </div>

          {{-- Dates --}}
          <div class="form-item">
            <label class="form-label">From <span class="subtle">*</span></label>
            <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" class="control" required>
          </div>

          <div class="form-item">
            <label class="form-label">To <span class="subtle">*</span></label>
            <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" class="control" required>
          </div>

          {{-- Date range preview --}}
          <div class="form-item span-2">
            <div class="range" id="rangePreview" aria-live="polite"></div>
          </div>

          {{-- Reason --}}
          <div class="form-item span-2">
            <label class="form-label">Reason / Description <span class="subtle">*</span></label>
            <textarea name="reason" class="control" rows="5" placeholder="Explain the reason and any details (e.g., medical, travel, family event)…" required>{{ old('reason') }}</textarea>
          </div>

          <div class="row-actions span-2">
            <button class="btn btn-primary" type="submit">Submit Request</button>
            <a class="btn btn-ghost" href="{{ route('student.vacation-requests.index') }}">Cancel</a>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>

<script>
  // Live date-range preview + simple client-side validation (end >= start)
  (function(){
    const start = document.getElementById('start_date');
    const end   = document.getElementById('end_date');
    const out   = document.getElementById('rangePreview');

    function fmt(d){
      if(!d) return '';
      const dx = new Date(d + 'T00:00:00');
      if(isNaN(dx)) return d;
      return dx.toLocaleDateString(undefined, { year:'numeric', month:'short', day:'numeric' });
    }

    function daysInclusive(a,b){
      const da = new Date(a + 'T00:00:00');
      const db = new Date(b + 'T00:00:00');
      if(isNaN(da) || isNaN(db)) return null;
      const diff = Math.round((db - da) / (1000*60*60*24));
      return diff >= 0 ? (diff + 1) : null; // inclusive days
    }

    function render(){
      const s = start.value, e = end.value;
      end.setCustomValidity('');
      out.innerHTML = '';

      if(!s && !e) return;

      if(s && e){
        const d = daysInclusive(s,e);
        if(d === null){
          end.setCustomValidity('End date must be the same or after the start date.');
          out.innerHTML = '<span class="badge warn">Select a valid range</span>';
        }else{
          out.innerHTML =
            '<span class="badge">Selected:</span>' +
            '<span class="badge">'+ fmt(s) +' → '+ fmt(e) +'</span>' +
            '<span class="badge ok">'+ d +' day'+(d>1?'s':'')+'</span>';
        }
      }else if(s){
        out.innerHTML =
          '<span class="badge">Start:</span><span class="badge">'+ fmt(s) +'</span>' +
          '<span class="badge warn">Pick end date</span>';
      }else if(e){
        out.innerHTML =
          '<span class="badge">End:</span><span class="badge">'+ fmt(e) +'</span>' +
          '<span class="badge warn">Pick start date</span>';
      }
    }

    start.addEventListener('change', render, { passive:true });
    end.addEventListener('change', render, { passive:true });
    start.addEventListener('input', render, { passive:true });
    end.addEventListener('input', render, { passive:true });

    render(); // initial
  })();
</script>
@endsection
