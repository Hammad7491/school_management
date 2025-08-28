{{-- resources/views/students/vacationrequests/index.blade.php --}}
@extends('students.layouts.app')

@section('content')
@php
  use Illuminate\Support\Str;

  $hasClass = !empty($student->schoolClass?->name);
  $total = (is_object($requests) && method_exists($requests,'total'))
            ? $requests->total()
            : (is_countable($requests) ? count($requests) : 0);
  $CLAMP = 160; // characters before "See more"
@endphp

<style>
  :root{
    --bg:#f7f9ff; --ink:#0b1020; --muted:#6b7280; --card:#ffffff; --stroke:rgba(15,23,42,.10);
    --brand1:#6a7bff; --brand2:#22d3ee; --radius:18px; --ring:rgba(106,123,255,.28);
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
  .wrap{ max-width:1000px; margin:0 auto; padding:24px 12px 72px; }

  /* Header */
  .title{ font-size: clamp(26px,5vw,44px); font-weight:900; margin:0; line-height:1.06; }
  .title span{ background:linear-gradient(90deg,var(--brand1),var(--brand2)); -webkit-background-clip:text; background-clip:text; color:transparent; }
  .subtle{ color:var(--muted); }

  .bar{ display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; margin-bottom:12px; }
  .chips{ display:flex; gap:8px; align-items:center; flex-wrap:wrap; }
  .chip{ display:inline-flex; align-items:center; gap:.45rem; padding:8px 10px; border-radius:999px; border:1px solid var(--stroke); background:var(--card); font-weight:800; color:var(--ink); }
  .chip .muted{ color:var(--muted); font-weight:700; }

  .btn{ border:0; border-radius:12px; padding:10px 14px; font-weight:900; cursor:pointer; }
  .btn-primary{ color:#fff; background:linear-gradient(90deg,var(--brand1),var(--brand2)); box-shadow:0 10px 22px rgba(106,123,255,.35); text-decoration:none; }
  .btn-primary:hover{ filter:brightness(1.05); transform: translateY(-1px); }

  /* Filters */
  .filters{ display:flex; gap:10px; align-items:center; flex-wrap:wrap; }
  .search{ position:relative; min-width:220px; flex:1 1 280px; max-width:520px; }
  .search input{
    width:100%; border:1px solid var(--stroke); border-radius:12px; padding:12px 14px 12px 40px;
    background:var(--card); color:var(--ink); outline:none;
  }
  .search input:focus{ box-shadow:0 0 0 4px var(--ring); border-color:transparent; }
  .search svg{ position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--muted); }
  .control{
    border:1px solid var(--stroke); border-radius:12px; padding:12px 14px; background:var(--card); color:var(--ink);
  }
  .control:focus{ outline:none; box-shadow:0 0 0 4px var(--ring); border-color:transparent; }

  /* Card shell */
  .cardx{ background:var(--card); border:1px solid var(--stroke); border-radius:var(--radius); box-shadow:0 18px 45px rgba(2,6,23,.08); }
  .cardx .body{ padding:16px; }
  .cardx .footer{ padding:12px 16px; border-top:1px solid var(--stroke); }

  /* Data table */
  .data-table{ width:100%; border-collapse:separate; border-spacing:0; }
  .data-table th, .data-table td{ padding:12px 16px; border-bottom:1px solid var(--stroke); vertical-align:top; }
  .data-table thead th{
    background:linear-gradient(180deg, rgba(106,123,255,.06), rgba(34,211,238,.06));
    font-weight:800; text-align:left;
  }
  .data-table tbody tr:hover{ background: rgba(106,123,255,.06); }

  .reason{ display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
  .link{ border:1px solid var(--stroke); background:transparent; color:var(--ink); padding:6px 10px; border-radius:10px; font-weight:800; display:inline-flex; gap:.35rem; align-items:center; text-decoration:none; cursor:pointer; }
  .link:hover{ box-shadow:0 0 0 4px var(--ring); }
  .muted{ color:var(--muted); }

  /* Status chips */
  .status{ padding:6px 10px; border-radius:999px; font-weight:900; font-size:12px; display:inline-block; }
  .st-pending{ color:#7c2d12; background:rgba(245,158,11,.14); border:1px solid rgba(245,158,11,.35); }
  .st-approved{ color:#065f46; background:rgba(16,185,129,.14); border:1px solid rgba(16,185,129,.35); }
  .st-rejected{ color:#7f1d1d; background:rgba(225,29,72,.14); border:1px solid rgba(225,29,72,.35); }

  .empty{ text-align:center; color:var(--muted); padding:36px 12px; }
  .hidden{ display:none !important; }

  /* Mobile: table -> stacked cards */
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
    .reason{ -webkit-line-clamp:4; }
  }

  /* Modal */
  .modalx{ position:fixed; inset:0; background:rgba(2,6,23,.55); display:none; align-items:center; justify-content:center; padding:16px; z-index:80; }
  .modalx.show{ display:flex; }
  .sheet{ width:min(680px, 92vw); max-height:88vh; background:var(--card); border:1px solid var(--stroke); border-radius:16px; box-shadow:0 24px 60px rgba(2,6,23,.3); display:flex; flex-direction:column; }
  .sheet .head{ display:flex; align-items:center; justify-content:space-between; gap:12px; padding:14px 16px; border-bottom:1px solid var(--stroke); font-weight:900; }
  .sheet .body{ padding:16px; overflow:auto; }
  .sheet pre{ white-space:pre-wrap; word-wrap:break-word; font-family:inherit; margin:0; }
  .close-btn{ border:1px solid var(--stroke); background:transparent; padding:8px 10px; border-radius:10px; font-weight:900; cursor:pointer; }
  .close-btn:hover{ box-shadow:0 0 0 4px var(--ring); }
</style>

<div class="page">
  <div class="wrap">

    {{-- Header --}}
    <div class="bar">
      <div>
        <h1 class="title">My <span>Leave Requests</span></h1>
        <div class="subtle">{{ $total }} on this page</div>
      </div>
      <a href="{{ route('student.vacation-requests.create') }}" class="btn btn-primary">+ New Request</a>
    </div>

    {{-- Student chips --}}
    <div class="bar" style="margin-top:-6px;">
      <div class="chips">
        <div class="chip"><span class="muted">Student:</span> {{ $student->name }}</div>
        <div class="chip"><span class="muted">Reg #:</span> {{ $student->reg_no }}</div>
        @if($hasClass)
          <div class="chip"><span class="muted">Class:</span> {{ $student->schoolClass?->name }}</div>
        @endif
      </div>
    </div>

    {{-- Filters --}}
    <div class="bar" style="margin-top:-8px;">
      <div class="filters" style="width:100%;">
        <div class="search">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
            <path d="m21 21-4.3-4.3M16 10.5A5.5 5.5 0 1 1 5 10.5a5.5 5.5 0 0 1 11 0Z"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          <input id="rqSearch" type="search" placeholder="Search by date or reason…">
        </div>
        <select id="rqStatus" class="control" style="min-width:160px;">
          <option value="">All statuses</option>
          <option value="pending">Pending</option>
          <option value="approved">Approved</option>
          <option value="rejected">Rejected</option>
        </select>
      </div>
    </div>

    {{-- Table / Card --}}
    <div class="cardx">
      <div class="body" style="padding:0;">
        <table class="data-table">
          <thead>
            <tr>
              <th>Date(s)</th>
              <th>Reason</th>
              <th>Status</th>
              <th class="text-end">Submitted</th>
            </tr>
          </thead>
          <tbody id="rqBody">
            @forelse($requests as $r)
              @php
                $status = strtolower($r->status ?? 'pending');
                $range = $r->start_date ? $r->start_date->format('Y-m-d') : '—';
                if ($r->end_date) $range .= ' → '.$r->end_date->format('Y-m-d');
                $needsMore = Str::length($r->reason ?? '') > $CLAMP;
                $modalId = 'reason-' . ($r->id ?? uniqid());
              @endphp
              <tr class="rq-row" data-status="{{ $status }}">
                <td data-label="Date(s)">{{ $range }}</td>

                <td data-label="Reason" style="max-width:560px">
                  <div class="reason">{{ Str::limit($r->reason, $CLAMP) }}</div>
                  @if($needsMore)
                    <button type="button" class="link" data-modal-open="{{ $modalId }}" aria-haspopup="dialog" aria-controls="{{ $modalId }}">
                      See more
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                  @endif
                </td>

                <td data-label="Status" class="text-nowrap">
                  @if($status === 'approved')
                    <span class="status st-approved">Approved</span>
                  @elseif($status === 'rejected')
                    <span class="status st-rejected">Rejected</span>
                  @else
                    <span class="status st-pending">Pending</span>
                  @endif
                </td>

                <td data-label="Submitted" class="text-end">{{ $r->created_at->diffForHumans() }}</td>
              </tr>

              {{-- Modal for this row --}}
              @if($needsMore)
                <div class="modalx" id="{{ $modalId }}" role="dialog" aria-modal="true" aria-labelledby="{{ $modalId }}-title">
                  <div class="sheet">
                    <div class="head">
                      <div id="{{ $modalId }}-title">Leave Reason</div>
                      <button type="button" class="close-btn" data-modal-close>Close</button>
                    </div>
                    <div class="body">
                      <pre>{{ $r->reason }}</pre>
                    </div>
                  </div>
                </div>
              @endif
            @empty
              <tr><td colspan="4" class="empty">No requests yet.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- Pagination --}}
      @if($requests->hasPages())
        <div class="footer">
          {{ $requests->links() }}
        </div>
      @endif
    </div>

  </div>
</div>

{{-- Client-side search + status filter + modal handler --}}
<script>
  (function(){
    // filters
    const q   = document.getElementById('rqSearch');
    const sel = document.getElementById('rqStatus');
    const rows = Array.from(document.querySelectorAll('#rqBody .rq-row'));

    function apply(){
      const term = (q.value || '').trim().toLowerCase();
      const st   = (sel.value || '').trim().toLowerCase();

      rows.forEach(row => {
        const hay = row.textContent.toLowerCase();
        const rst = (row.getAttribute('data-status') || '').toLowerCase();
        const matchText = !term || hay.includes(term);
        const matchSt   = !st || rst === st;
        row.classList.toggle('hidden', !(matchText && matchSt));
      });
    }
    q.addEventListener('input', apply, { passive:true });
    sel.addEventListener('change', apply, { passive:true });

    // modals
    function closeModal(modal){
      if(!modal) return;
      modal.classList.remove('show');
      document.body.style.overflow = '';
    }
    function openModal(modal){
      if(!modal) return;
      modal.classList.add('show');
      document.body.style.overflow = 'hidden';
    }

    document.addEventListener('click', function(e){
      const openBtn = e.target.closest('[data-modal-open]');
      if(openBtn){
        const id = openBtn.getAttribute('data-modal-open');
        const modal = document.getElementById(id);
        openModal(modal);
      }
      const closeBtn = e.target.closest('[data-modal-close]');
      if(closeBtn){
        closeModal(closeBtn.closest('.modalx'));
      }
    });

    document.querySelectorAll('.modalx').forEach(m => {
      m.addEventListener('click', (e) => {
        if(e.target === m){ closeModal(m); }
      });
    });
    document.addEventListener('keydown', (e) => {
      if(e.key === 'Escape'){
        document.querySelectorAll('.modalx.show').forEach(closeModal);
      }
    });
  })();
</script>
@endsection
