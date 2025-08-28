{{-- resources/views/admin/vacations/index.blade.php --}}
@extends('layouts.app')

@section('content')
@php
  use Illuminate\Support\Str;

  $total = (is_object($requests) && method_exists($requests,'total'))
            ? $requests->total()
            : (is_countable($requests) ? count($requests) : 0);

  $TRUNC = 140; // characters to show before “See more”
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

  .title{ font-size: clamp(28px,5.5vw,56px); font-weight:900; margin:0; line-height:1.04; letter-spacing:.2px; }
  .title span{ background:linear-gradient(90deg,var(--brand1),var(--brand2)); -webkit-background-clip:text; background-clip:text; color:transparent; }
  .subtle{ color:var(--muted); font-weight:600; }

  .bar{ display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; margin-bottom:14px; }

  /* Filters */
  .filters{ display:flex; gap:10px; align-items:center; flex-wrap:wrap; width:100%; }
  .search{ position:relative; min-width:240px; flex:1 1 320px; max-width:520px; }
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

  /* Card/Table */
  .cardx{ background:var(--card); border:1px solid var(--stroke); border-radius:var(--radius); box-shadow:0 20px 55px rgba(2,6,23,.08); }
  .cardx .body{ padding:0; }
  .cardx .footer{ padding:12px 16px; border-top:1px solid var(--stroke); }

  .data-table{ width:100%; border-collapse:separate; border-spacing:0; }
  .data-table th, .data-table td{ padding:14px 16px; border-bottom:1px solid var(--stroke); vertical-align:top; }
  .data-table thead th{
    background:linear-gradient(180deg, rgba(106,123,255,.06), rgba(34,211,238,.06));
    font-weight:800; text-align:left;
  }
  .data-table tbody tr:hover{ background: rgba(106,123,255,.06); }

  /* Reason cell */
  .reason-wrap{ display:flex; align-items:center; justify-content:space-between; gap:10px; }
  .reason-text{
    flex:1 1 auto; min-width:0;
    display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;
    overflow-wrap:anywhere; word-break:break-word;
  }
  .see-more{
    white-space:nowrap; border:1px solid var(--stroke); background:transparent; color:var(--ink);
    padding:6px 12px; border-radius:10px; font-weight:800; display:inline-flex; gap:.35rem; align-items:center;
  }
  .see-more:hover{ box-shadow:0 0 0 4px var(--ring); }

  /* Soft status chips */
  .badge-soft{ border-radius:999px; padding:6px 10px; font-weight:900; font-size:12px; display:inline-block; border:1px solid; }
  .bg-pending{ color:#7c2d12; background:rgba(245,158,11,.14); border-color:rgba(245,158,11,.35); }
  .bg-approved{ color:#065f46; background:rgba(16,185,129,.14); border-color:rgba(16,185,129,.35); }
  .bg-rejected{ color:#7f1d1d; background:rgba(225,29,72,.14); border-color:rgba(225,29,72,.35); }

  .actions .btn{ margin:2px 0; }
  @media (max-width: 720px){ .actions .btn{ width:100%; } }

  .empty{ text-align:center; color:var(--muted); padding:36px 12px; }
  .hidden{ display:none !important; }

  /* Modal body wrapping */
  .modal-body{ overflow-wrap:anywhere; word-break:break-word; }

  /* Mobile: table -> stacked cards */
  @media (max-width: 720px){
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
    .reason-wrap{ flex-direction:column; align-items:flex-start; }
    .see-more{ margin-top:6px; }
  }
</style>

<div class="page">
  <div class="wrap">

    {{-- Header --}}
    <div class="bar">
      <div>
        <h1 class="title">Vacation <span>Requests</span></h1>
        <div class="subtle">{{ $total }} in total</div>
      </div>
    </div>

    {{-- Filters --}}
    <div class="bar" style="margin-top:-4px;">
      <div class="filters">
        <div class="search">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
            <path d="m21 21-4.3-4.3M16 10.5A5.5 5.5 0 1 1 5 10.5a5.5 5.5 0 0 1 11 0Z"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          <input id="vrSearch" type="search" placeholder="Search by student, reg #, class or reason…">
        </div>
        <select id="vrStatus" class="control" style="min-width:160px;">
          <option value="">All statuses</option>
          <option value="pending">Pending</option>
          <option value="approved">Approved</option>
          <option value="rejected">Rejected</option>
        </select>
      </div>
    </div>

    {{-- Table / Cards --}}
    <div class="cardx">
      <div class="body">
        <table class="data-table">
          <thead>
            <tr>
              <th>Student</th>
              <th>Reg #</th>
              <th>Class</th>
              <th style="width:38%">Reason</th>
              <th>Status</th>
              <th>Submitted</th>
              <th style="width:220px;">Action</th>
            </tr>
          </thead>
          <tbody id="vrBody">
            @forelse($requests as $r)
              @php
                $statusNorm = strtolower($r->status ?? 'pending'); // pending|approved|rejected
                $badgeClass = $statusNorm === 'approved' ? 'bg-approved' : ($statusNorm === 'rejected' ? 'bg-rejected' : 'bg-pending');
                $range = $r->start_date ? $r->start_date->format('Y-m-d') : '—';
                if ($r->end_date) $range .= ' → '.$r->end_date->format('Y-m-d');
                $long   = Str::length($r->reason) > $TRUNC;
                $short  = Str::limit(strip_tags($r->reason), $TRUNC);
              @endphp

              <tr class="vr-row" data-status="{{ $statusNorm }}">
                <td data-label="Student">{{ $r->student_name }}</td>
                <td data-label="Reg #">{{ $r->reg_no }}</td>
                <td data-label="Class">{{ $r->class?->name ?? '—' }}</td>

                {{-- Reason + See more (single modal approach) --}}
                <td data-label="Reason" style="white-space:normal; word-wrap:break-word;">
                  <div class="reason-wrap">
                    <span class="reason-text">{{ $long ? $short : $r->reason }}</span>
                    @if($long)
                      <button type="button"
                              class="see-more"
                              data-id="r{{ $r->id }}"
                              data-name="{{ $r->student_name }}"
                              data-reg="{{ $r->reg_no }}"
                              data-range="{{ $range }}">
                        See more
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                          <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                      </button>
                    @endif
                  </div>
                  {{-- Full reason kept hidden to avoid HTML entity issues --}}
                  <span class="d-none reason-full" id="r{{ $r->id }}">{{ $r->reason }}</span>
                </td>

                <td data-label="Status">
                  <span class="badge-soft {{ $badgeClass }}">{{ ucfirst($statusNorm) }}</span>
                </td>

                <td data-label="Submitted">{{ $r->created_at?->diffForHumans() ?? '—' }}</td>

                <td data-label="Action">
                  <div class="actions d-grid d-md-flex gap-2">
                    @if($statusNorm === 'pending')
                      <form method="POST"
                            action="{{ route('admin.vacations.updateStatus', [$r->id, 'approved']) }}"
                            onsubmit="return confirm('Accept this request?');">
                        @csrf
                        <button class="btn btn-success btn-sm w-100">
                          <i class="bi bi-check-circle"></i> Accept
                        </button>
                      </form>
                      <form method="POST"
                            action="{{ route('admin.vacations.updateStatus', [$r->id, 'rejected']) }}"
                            onsubmit="return confirm('Reject this request?');">
                        @csrf
                        <button class="btn btn-danger btn-sm w-100">
                          <i class="bi bi-x-circle"></i> Reject
                        </button>
                      </form>
                    @else
                      <span class="badge bg-secondary">Final: {{ ucfirst($statusNorm) }}</span>
                    @endif
                  </div>

                  @if($range && $range !== '—')
                    <div class="d-block d-md-none mt-2 text-muted small">
                      <strong>Date(s):</strong> {{ $range }}
                    </div>
                  @endif
                </td>
              </tr>
            @empty
              <tr><td colspan="7" class="empty">No vacation requests yet.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if(is_object($requests) && method_exists($requests,'hasPages') && $requests->hasPages())
        <div class="footer">
          {{ $requests->links() }}
        </div>
      @endif
    </div>

  </div>
</div>

{{-- Modal (single, reused) --}}
<div class="modal fade" id="vrReasonModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="vrReasonTitle">Full Reason</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="small text-muted mb-2" id="vrReasonMeta"></div>
        <div id="vrReasonText" style="white-space:pre-wrap;"></div>
      </div>
    </div>
  </div>
</div>

{{-- Live search + status filter + see-more modal wiring --}}
<script>
  (function(){
    const q   = document.getElementById('vrSearch');
    const sel = document.getElementById('vrStatus');
    const rows = Array.from(document.querySelectorAll('#vrBody .vr-row'));

    function apply(){
      const term = (q.value || '').trim().toLowerCase();
      const st   = (sel.value || '').trim().toLowerCase();

      rows.forEach(row => {
        const hay = row.textContent.toLowerCase();
        const rst = (row.getAttribute('data-status') || '').toLowerCase();
        const okText = !term || hay.includes(term);
        const okSt   = !st || rst === st;
        row.classList.toggle('hidden', !(okText && okSt));
      });
    }
    q.addEventListener('input', apply, { passive:true });
    sel.addEventListener('change', apply, { passive:true });

    // See more modal (single)
    const title = document.getElementById('vrReasonTitle');
    const meta  = document.getElementById('vrReasonMeta');
    const body  = document.getElementById('vrReasonText');
    const modalEl = document.getElementById('vrReasonModal');
    let bsModal = null;

    function openModal(name, reg, range, fullId){
      const hidden = document.getElementById(fullId);
      const fullText = hidden ? hidden.textContent : '';
      title.textContent = `Full Reason — ${name} (Reg # ${reg})`;
      meta.textContent  = range && range !== '—' ? `Date(s): ${range}` : '';
      body.textContent  = fullText;

      if (!bsModal) {
        // requires Bootstrap JS loaded on the layout
        bsModal = new bootstrap.Modal(modalEl);
      }
      bsModal.show();
    }

    document.addEventListener('click', function(e){
      const btn = e.target.closest('.see-more');
      if (!btn) return;
      openModal(btn.dataset.name, btn.dataset.reg, btn.dataset.range, btn.dataset.id);
    });
  })();
</script>
@endsection
