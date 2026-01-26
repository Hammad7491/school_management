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
  /* Scoped variables (won't affect other pages) */
  .vr-page{
    --bg:#f7f9ff; --ink:#0b1020; --muted:#6b7280; --card:#ffffff; --stroke:rgba(15,23,42,.10);
    --brand1:#6a7bff; --brand2:#22d3ee; --radius:18px; --ring:rgba(106,123,255,.28);
    --shadow:0 20px 55px rgba(2,6,23,.08);
    min-height:100dvh;
    background:
      radial-gradient(800px 400px at -10% 0%, rgba(106,123,255,.12), transparent 60%),
      radial-gradient(700px 500px at 110% -10%, rgba(34,211,238,.12), transparent 60%),
      var(--bg);
    color:var(--ink);
  }
  @media (prefers-color-scheme: dark){
    .vr-page{
      --bg:#0b1020; --ink:#e6e9f5; --muted:#9aa3ba; --card:#0f1830;
      --stroke:rgba(255,255,255,.12); --ring:rgba(106,123,255,.45);
    }
  }

  .vr-wrap{ max-width:1180px; margin:0 auto; padding:24px 12px 72px; }

  /* Header */
  .vr-bar{ display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; }
  .vr-title{
    font-size:clamp(28px,5.5vw,56px);
    font-weight:900;
    margin:0;
    line-height:1.04;
    letter-spacing:.2px;
  }
  .vr-title span{
    background:linear-gradient(90deg,var(--brand1),var(--brand2));
    -webkit-background-clip:text;
    background-clip:text;
    color:transparent;
  }
  .vr-subtle{ color:var(--muted); font-weight:700; margin-top:6px; }

  /* Filters */
  .vr-filters{ display:flex; gap:10px; align-items:center; flex-wrap:wrap; width:100%; margin-top:10px; }
  .vr-search{ position:relative; min-width:240px; flex:1 1 320px; max-width:560px; }
  .vr-search input{
    width:100%;
    border:1px solid var(--stroke);
    border-radius:12px;
    padding:12px 14px 12px 40px;
    background:var(--card);
    color:var(--ink);
    outline:none;
  }
  .vr-search input:focus{ box-shadow:0 0 0 4px var(--ring); border-color:transparent; }
  .vr-search svg{ position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--muted); }
  .vr-control{
    border:1px solid var(--stroke);
    border-radius:12px;
    padding:12px 14px;
    background:var(--card);
    color:var(--ink);
    min-width:170px;
  }
  .vr-control:focus{ outline:none; box-shadow:0 0 0 4px var(--ring); border-color:transparent; }

  /* Container */
  .vr-card{
    margin-top:14px;
    background:var(--card);
    border:1px solid var(--stroke);
    border-radius:var(--radius);
    box-shadow:var(--shadow);
    overflow:hidden;
  }
  .vr-card-head{
    padding:12px 16px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:10px;
    font-size:12px;
    color:var(--muted);
  }
  .vr-footer{
    padding:12px 16px;
    border-top:1px solid var(--stroke);
  }

  /* Table */
  .vr-table-scroll{ overflow-x:auto; }
  .vr-table{ width:100%; border-collapse:separate; border-spacing:0; min-width:980px; }
  .vr-table th, .vr-table td{ padding:14px 16px; border-bottom:1px solid var(--stroke); vertical-align:top; }
  .vr-table thead th{
    background:linear-gradient(180deg, rgba(106,123,255,.06), rgba(34,211,238,.06));
    font-weight:900;
    text-align:left;
    white-space:nowrap;
  }
  .vr-table tbody tr:hover{ background:rgba(106,123,255,.06); }

  /* Reason cell */
  .reason-wrap{ display:flex; align-items:flex-start; justify-content:space-between; gap:10px; }
  .reason-text{
    flex:1 1 auto;
    min-width:0;
    display:-webkit-box;
    -webkit-line-clamp:2;
    -webkit-box-orient:vertical;
    overflow:hidden;
    overflow-wrap:anywhere;
    word-break:break-word;
  }
  .see-more{
    white-space:nowrap;
    border:1px solid var(--stroke);
    background:transparent;
    color:var(--ink);
    padding:6px 12px;
    border-radius:10px;
    font-weight:900;
    display:inline-flex;
    gap:.35rem;
    align-items:center;
  }
  .see-more:hover{ box-shadow:0 0 0 4px var(--ring); }

  /* Soft status chips */
  .badge-soft{
    border-radius:999px;
    padding:6px 10px;
    font-weight:900;
    font-size:12px;
    display:inline-block;
    border:1px solid;
    white-space:nowrap;
  }
  .bg-pending{ color:#7c2d12; background:rgba(245,158,11,.14); border-color:rgba(245,158,11,.35); }
  .bg-approved{ color:#065f46; background:rgba(16,185,129,.14); border-color:rgba(16,185,129,.35); }
  .bg-rejected{ color:#7f1d1d; background:rgba(225,29,72,.14); border-color:rgba(225,29,72,.35); }

  .hidden{ display:none !important; }

  /* Modal body wrapping */
  .modal-body{ overflow-wrap:anywhere; word-break:break-word; }

  /* ✅ Mobile cards (true responsive; no table hacks) */
  .vr-cards{ display:none; padding:12px; }
  .vr-card-item{
    border:1px solid var(--stroke);
    border-radius:16px;
    background:var(--card);
    box-shadow:0 14px 26px rgba(2,6,23,.06);
    padding:14px;
  }
  .vr-top{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:12px;
  }
  .vr-who{ min-width:0; }
  .vr-name{ font-weight:900; }
  .vr-mini{ color:var(--muted); font-weight:700; font-size:12px; }
  .vr-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:10px 12px;
    margin-top:12px;
  }
  .vr-label{
    font-size:11px;
    color:var(--muted);
    text-transform:uppercase;
    letter-spacing:.08em;
    font-weight:900;
    margin-bottom:4px;
  }
  .vr-val{ font-weight:800; }
  .vr-actions{ display:grid; gap:8px; margin-top:12px; }
  .vr-actions form{ margin:0; }

  .vr-empty{ text-align:center; color:var(--muted); padding:36px 12px; }

  /* Breakpoints */
  @media (max-width: 992px){
    .vr-table{ min-width:900px; }
  }
  @media (max-width: 720px){
    .vr-table-scroll{ display:none; }
    .vr-cards{ display:block; }
    .vr-grid{ grid-template-columns:1fr; }
    .vr-control{ min-width:160px; flex:1 1 160px; }
    .vr-search{ max-width:none; flex:1 1 100%; }
  }
</style>


<div class="vr-page">
  <div class="vr-wrap">

    {{-- Header --}}
    <div class="vr-bar">
      <div>
        <h1 class="vr-title">Vacation <span>Requests</span></h1>
        <div class="vr-subtle">{{ $total }} in total</div>
      </div>
    </div>

    {{-- Filters --}}
    <div class="vr-filters">
      <div class="vr-search">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path d="m21 21-4.3-4.3M16 10.5A5.5 5.5 0 1 1 5 10.5a5.5 5.5 0 0 1 11 0Z"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <input id="vrSearch" type="search" placeholder="Search by student, reg #, class or reason…">
      </div>

      <select id="vrStatus" class="vr-control">
        <option value="">All statuses</option>
        <option value="pending">Pending</option>
        <option value="approved">Approved</option>
        <option value="rejected">Rejected</option>
      </select>
    </div>

    {{-- List --}}
    <div class="vr-card">
      <div class="vr-card-head">
        <span>Vacation requests</span>
        <span>Search and filter updates instantly.</span>
      </div>

      @php
        // Helps search match include date range too
        $fmt = fn($d) => $d ? $d->format('Y-m-d') : null;
      @endphp

      {{-- ✅ Desktop/Tablet table --}}
      <div class="vr-table-scroll">
        <table class="vr-table">
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
          <tbody id="vrBodyTable">
            @forelse($requests as $r)
              @php
                $statusNorm = strtolower($r->status ?? 'pending');
                $badgeClass = $statusNorm === 'approved' ? 'bg-approved' : ($statusNorm === 'rejected' ? 'bg-rejected' : 'bg-pending');

                $range = $fmt($r->start_date) ?? '—';
                if ($fmt($r->end_date)) $range .= ' → '.$fmt($r->end_date);

                $long  = Str::length($r->reason) > $TRUNC;
                $short = Str::limit(strip_tags($r->reason), $TRUNC);

                $className = $r->class?->name ?? '—';
                $submitted = $r->created_at?->diffForHumans() ?? '—';
              @endphp

              <tr class="vr-row"
                  data-status="{{ $statusNorm }}"
                  data-search="{{ strtolower($r->student_name.' '.$r->reg_no.' '.$className.' '.$r->reason.' '.$range) }}">
                <td>{{ $r->student_name }}</td>
                <td>{{ $r->reg_no }}</td>
                <td>{{ $className }}</td>

                <td style="white-space:normal; word-wrap:break-word;">
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
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                          <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                      </button>
                    @endif
                  </div>
                  <span class="d-none reason-full" id="r{{ $r->id }}">{{ $r->reason }}</span>
                </td>

                <td><span class="badge-soft {{ $badgeClass }}">{{ ucfirst($statusNorm) }}</span></td>

                <td>{{ $submitted }}</td>

                <td>
                  <div class="d-grid d-md-flex gap-2">
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
                    <div class="mt-2 text-muted small">
                      <strong>Date(s):</strong> {{ $range }}
                    </div>
                  @endif
                </td>
              </tr>
            @empty
              <tr><td colspan="7" class="vr-empty">No vacation requests yet.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- ✅ Mobile cards --}}
      <div class="vr-cards" id="vrCards">
        @forelse($requests as $r)
          @php
            $statusNorm = strtolower($r->status ?? 'pending');
            $badgeClass = $statusNorm === 'approved' ? 'bg-approved' : ($statusNorm === 'rejected' ? 'bg-rejected' : 'bg-pending');

            $range = $fmt($r->start_date) ?? '—';
            if ($fmt($r->end_date)) $range .= ' → '.$fmt($r->end_date);

            $long  = Str::length($r->reason) > $TRUNC;
            $short = Str::limit(strip_tags($r->reason), $TRUNC);

            $className = $r->class?->name ?? '—';
            $submitted = $r->created_at?->diffForHumans() ?? '—';
          @endphp

          <div class="vr-card-item vr-row"
               data-status="{{ $statusNorm }}"
               data-search="{{ strtolower($r->student_name.' '.$r->reg_no.' '.$className.' '.$r->reason.' '.$range) }}">
            <div class="vr-top">
              <div class="vr-who">
                <div class="vr-name">{{ $r->student_name }}</div>
                <div class="vr-mini">Reg # {{ $r->reg_no }} • Class: {{ $className }}</div>
              </div>
              <div>
                <span class="badge-soft {{ $badgeClass }}">{{ ucfirst($statusNorm) }}</span>
              </div>
            </div>

            <div class="vr-grid">
              <div>
                <div class="vr-label">Reason</div>
                <div class="vr-val" style="font-weight:700; white-space:normal;">
                  {{ $long ? $short : $r->reason }}
                </div>

                @if($long)
                  <button type="button"
                          class="see-more mt-2"
                          data-id="r{{ $r->id }}"
                          data-name="{{ $r->student_name }}"
                          data-reg="{{ $r->reg_no }}"
                          data-range="{{ $range }}">
                    See more
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                      <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </button>
                @endif

                <span class="d-none reason-full" id="r{{ $r->id }}">{{ $r->reason }}</span>
              </div>

              <div>
                <div class="vr-label">Dates</div>
                <div class="vr-val">{{ $range }}</div>
              </div>

              <div>
                <div class="vr-label">Submitted</div>
                <div class="vr-val">{{ $submitted }}</div>
              </div>
            </div>

            <div class="vr-actions">
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
          </div>
        @empty
          <div class="vr-empty">No vacation requests yet.</div>
        @endforelse
      </div>

      @if(is_object($requests) && method_exists($requests,'hasPages') && $requests->hasPages())
        <div class="vr-footer">
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

<script>
(function(){
  const q   = document.getElementById('vrSearch');
  const sel = document.getElementById('vrStatus');

  function rows(){
    return Array.from(document.querySelectorAll('.vr-row'));
  }

  function apply(){
    const term = (q.value || '').trim().toLowerCase();
    const st   = (sel.value || '').trim().toLowerCase();

    rows().forEach(el => {
      const hay = (el.getAttribute('data-search') || el.textContent || '').toLowerCase();
      const rst = (el.getAttribute('data-status') || '').toLowerCase();

      const okText = !term || hay.includes(term);
      const okSt   = !st || rst === st;

      el.classList.toggle('hidden', !(okText && okSt));
    });
  }

  q.addEventListener('input', apply, { passive:true });
  sel.addEventListener('change', apply, { passive:true });

  // See more modal (single)
  const title   = document.getElementById('vrReasonTitle');
  const meta    = document.getElementById('vrReasonMeta');
  const body    = document.getElementById('vrReasonText');
  const modalEl = document.getElementById('vrReasonModal');
  let bsModal   = null;

  function openModal(name, reg, range, fullId){
    const hidden  = document.getElementById(fullId);
    const fullTxt = hidden ? hidden.textContent : '';

    title.textContent = `Full Reason — ${name} (Reg # ${reg})`;
    meta.textContent  = (range && range !== '—') ? `Date(s): ${range}` : '';
    body.textContent  = fullTxt;

    if(!bsModal){
      bsModal = new bootstrap.Modal(modalEl);
    }
    bsModal.show();
  }

  document.addEventListener('click', function(e){
    const btn = e.target.closest('.see-more');
    if(!btn) return;
    openModal(btn.dataset.name, btn.dataset.reg, btn.dataset.range, btn.dataset.id);
  });

  // Initial
  apply();
})();
</script>
@endsection
