{{-- resources/views/students/homeworks/index.blade.php --}}
@extends('students.layouts.app')

@section('content')
@php
  $total = (is_object($homeworks) && method_exists($homeworks,'total'))
            ? $homeworks->total()
            : (is_countable($homeworks) ? count($homeworks) : 0);

  $previewWords = 10;

  $countWords = function(string $text): int {
    $text = trim($text);
    if ($text === '') return 0;
    $parts = preg_split('/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY);
    return is_array($parts) ? count($parts) : 0;
  };
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

  .title{ font-size: clamp(28px,5.2vw,48px); font-weight:900; line-height:1.05; margin:0; }
  .title span{ background:linear-gradient(90deg,var(--brand1),var(--brand2)); -webkit-background-clip:text; background-clip:text; color:transparent; }
  .subtle{ color:var(--muted); font-weight:600; }

  .bar{ display:flex; align-items:center; gap:12px; justify-content:space-between; flex-wrap:wrap; margin:6px 0 16px; }
  .search{ position:relative; min-width:240px; flex:1 1 280px; max-width:520px; }
  .search input{
    width:100%; border:1px solid var(--stroke); border-radius:12px; padding:12px 14px 12px 40px;
    background:var(--card); color:var(--ink); outline:none;
  }
  .search input:focus{ box-shadow:0 0 0 4px var(--ring); border-color:transparent; }
  .search svg{ position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--muted); }

  .cardx{ background:var(--card); border:1px solid var(--stroke); border-radius:var(--radius); box-shadow:0 18px 45px rgba(2,6,23,.08); }
  .cardx .body{ padding:0; }
  .cardx .footer{ padding:12px 16px; border-top:1px solid var(--stroke); }

  .data-table{ width:100%; border-collapse:separate; border-spacing:0; table-layout:fixed; }
  .data-table th, .data-table td{ padding:14px 16px; border-bottom:1px solid var(--stroke); vertical-align:top; }
  .data-table thead th{ background:linear-gradient(180deg, rgba(106,123,255,.06), rgba(34,211,238,.06)); font-weight:800; text-align:left; }
  .data-table tbody tr:hover{ background: rgba(106,123,255,.06); }

  .btn-link{
    display:inline-block; padding:8px 12px; border-radius:10px; border:1px solid var(--stroke); text-decoration:none;
    color:var(--ink); font-weight:800; background:transparent;
  }
  .btn-link:hover{ box-shadow:0 0 0 4px var(--ring); }

  /* ✅ COMMENT PREVIEW */
  .comment-cell{ max-width: 420px; }
  .comment-preview{
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
    max-width:100%;
    overflow-wrap:anywhere;
    word-break:break-word;
  }

  .see-more{
    display:inline-block;
    margin-top:10px;
    border:1px solid rgba(15,23,42,.10);
    background:transparent;
    color:#111827;
    font-weight:900;
    cursor:pointer;
    padding:10px 16px;
    border-radius:14px;
  }
  .see-more:hover{ box-shadow:0 0 0 4px var(--ring); }

  .empty{ text-align:center; color:var(--muted); padding:36px 12px; }

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

    .comment-cell{ max-width:none; }
    .comment-preview{ white-space:normal; }
    .btn-link{ width:100%; text-align:center; }
    .see-more{ width:100%; text-align:center; }
  }

  .hidden{ display:none !important; }

  /* ✅ MODAL (ONLY COMMENT) */
  .modalx{ position:fixed; inset:0; display:none; z-index:9999; }
  .modalx.open{ display:block; }
  .modalx .overlay{ position:absolute; inset:0; background:rgba(2,6,23,.55); }

  .modalx .panel{
    position:relative;
    width:min(980px, 92vw);
    max-height: 86vh;
    margin: 7vh auto 0;
    background:var(--card);
    border:1px solid var(--stroke);
    border-radius:16px;
    box-shadow:0 24px 70px rgba(2,6,23,.30);
    overflow:hidden;
  }

  .modal-head{
    display:flex;
    align-items:center;
    justify-content:flex-end;
    padding:16px 18px;
    border-bottom:1px solid var(--stroke);
  }

  .modal-close{
    border:0;
    background:transparent;
    font-size:38px;
    line-height:1;
    cursor:pointer;
    color:rgba(15,23,42,.55);
    padding:6px 10px;
    border-radius:12px;
  }
  .modal-close:hover{ box-shadow:0 0 0 4px var(--ring); color:var(--ink); }

  .modal-body{
    padding: 18px 22px 22px;
    max-height: calc(86vh - 70px);
    overflow:auto;
  }

  .modal-comment{
    font-size: 20px;
    line-height: 1.7;
    color: var(--ink);
    white-space: pre-wrap;
    overflow-wrap:anywhere;
    word-break:break-word;
  }
  @media (max-width:700px){
    .modal-comment{ font-size: 17px; }
  }
</style>

<div class="page">
  <div class="wrap">
    <div class="bar">
      <div>
        <h1 class="title">Homework <span>Schedule</span></h1>
        <div class="subtle">{{ $total }} on this page</div>
      </div>

      <div class="search">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
          <path d="m21 21-4.3-4.3M16 10.5A5.5 5.5 0 1 1 5 10.5a5.5 5.5 0 0 1 11 0Z"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <input id="hwSearch" type="search" placeholder="Search by day, class, course or comment…">
      </div>
    </div>

    <div class="cardx">
      <div class="body">
        <table class="data-table">
          <thead>
            <tr>
              <th style="width:140px;">Day</th>
              <th style="width:120px;">Class</th>
              <th style="width:140px;">Course</th>
              <th>Comment</th>
              <th style="width:220px;">File</th>
            </tr>
          </thead>

          <tbody id="hwBody">
            @forelse($homeworks as $h)
              @php
                $day = $h->day ? \Carbon\Carbon::parse($h->day)->format('Y-m-d') : '—';
                $cls = $h->schoolClass?->name ?? '—';
                $crs = $h->course?->name ?? '—';

                $full = (string)($h->comment ?? '');
                $fullTrim = trim($full);

                $wordsCount = $countWords($fullTrim);
                $isLong = $wordsCount > $previewWords;

                $short = $fullTrim ? \Illuminate\Support\Str::words($fullTrim, $previewWords, '…') : '—';

                $fileName = $h->file_name ?? ($h->file_path ? basename($h->file_path) : '');
                $searchHay = strtolower(trim($day.' '.$cls.' '.$crs.' '.$fullTrim.' '.$fileName));
              @endphp

              <tr class="hw-row" data-search="{{ e($searchHay) }}">
                <td data-label="Day">{{ $day }}</td>
                <td data-label="Class">{{ $cls }}</td>
                <td data-label="Course">{{ $crs }}</td>

                <td data-label="Comment" class="comment-cell">
                  <div class="comment-preview">{{ $short }}</div>

                  @if($isLong)
                    <button
                      type="button"
                      class="see-more js-see-more"
                      data-comment="{{ e($fullTrim) }}"
                    >
                      See more &nbsp;›
                    </button>
                  @endif
                </td>

                <td data-label="File">
                  @if($h->file_path)
                    <a class="btn-link" href="{{ route('homeworks.download',$h->id) }}">
                      {{ $fileName ?: 'Download' }}
                    </a>
                  @else
                    <span class="subtle">—</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="empty">No homework yet.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if(is_object($homeworks) && method_exists($homeworks,'hasPages') && $homeworks->hasPages())
        <div class="footer">{{ $homeworks->links() }}</div>
      @endif
    </div>
  </div>
</div>

{{-- Popup Modal (ONLY COMMENT) --}}
<div id="commentModal" class="modalx" aria-hidden="true" role="dialog" aria-modal="true">
  <div class="overlay js-close-modal"></div>

  <div class="panel" role="document">
    <div class="modal-head">
      <button type="button" class="modal-close js-close-modal" aria-label="Close">×</button>
    </div>

    <div class="modal-body">
      <div id="modalComment" class="modal-comment"></div>
    </div>
  </div>
</div>

<script>
  // Search filter
  (function(){
    const q = document.getElementById('hwSearch');
    const rows = Array.from(document.querySelectorAll('#hwBody .hw-row'));
    if(!q) return;

    function filter(){
      const term = (q.value || '').trim().toLowerCase();
      rows.forEach(row => {
        const hay = (row.dataset.search || '');
        row.classList.toggle('hidden', term && !hay.includes(term));
      });
    }
    q.addEventListener('input', filter, { passive:true });
  })();

  // Modal open/close (ONLY COMMENT)
  (function(){
    const modal = document.getElementById('commentModal');
    const modalComment = document.getElementById('modalComment');

    function openModal(comment){
      modalComment.textContent = comment || '';
      modal.classList.add('open');
      modal.setAttribute('aria-hidden', 'false');
      document.body.style.overflow = 'hidden';
    }

    function closeModal(){
      modal.classList.remove('open');
      modal.setAttribute('aria-hidden', 'true');
      modalComment.textContent = '';
      document.body.style.overflow = '';
    }

    document.addEventListener('click', (e) => {
      const btn = e.target.closest('.js-see-more');
      if(btn){
        openModal(btn.dataset.comment || '');
        return;
      }
      if(e.target.closest('.js-close-modal')) closeModal();
    });

    document.addEventListener('keydown', (e) => {
      if(e.key === 'Escape' && modal.classList.contains('open')) closeModal();
    });
  })();
</script>
@endsection
