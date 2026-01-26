{{-- resources/views/admin/notifications/index.blade.php --}}
@extends('layouts.app')

@section('content')
@php
  use Illuminate\Support\Str;

  $total = (is_object($notifications) && method_exists($notifications,'total'))
            ? $notifications->total()
            : (is_countable($notifications) ? count($notifications) : 0);

  $TRUNC = 120;
@endphp

@push('styles')
<style>
  /* ✅ Scoped styles (won't break other pages) */
  .nt-page{
    --bg:#f7f9ff; --ink:#0b1020; --muted:#6b7280; --card:#ffffff; --stroke:rgba(15,23,42,.10);
    --brand1:#6a7bff; --brand2:#22d3ee; --ring:rgba(106,123,255,.28); --radius:18px;
    --shadow:0 20px 55px rgba(2,6,23,.08);
    min-height:100dvh;
    background:
      radial-gradient(800px 400px at -10% 0%, rgba(106,123,255,.12), transparent 60%),
      radial-gradient(700px 500px at 110% -10%, rgba(34,211,238,.12), transparent 60%),
      var(--bg);
    color:var(--ink);
  }
  @media (prefers-color-scheme: dark){
    .nt-page{
      --bg:#0b1020; --ink:#e6e9f5; --muted:#9aa3ba; --card:#0f1830;
      --stroke:rgba(255,255,255,.12); --ring:rgba(106,123,255,.45);
    }
  }

  .nt-wrap{ max-width:1180px; margin:0 auto; padding:24px 12px 72px; }

  /* Header */
  .nt-bar{ display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; margin-bottom:14px; }
  .nt-title{
    font-size:clamp(28px,5.4vw,54px);
    font-weight:900;
    line-height:1.05;
    margin:0;
  }
  .nt-title span{
    background:linear-gradient(90deg,var(--brand1),var(--brand2));
    -webkit-background-clip:text;
    background-clip:text;
    color:transparent;
  }
  .nt-subtle{ color:var(--muted); font-weight:700; margin-top:6px; }

  /* Primary action */
  .nt-btn-primary{
    border:0;
    border-radius:12px;
    padding:10px 16px;
    font-weight:900;
    color:#fff;
    background:linear-gradient(90deg,var(--brand1),var(--brand2));
    box-shadow:0 12px 28px rgba(106,123,255,.35);
    text-decoration:none;
    display:inline-flex;
    align-items:center;
    gap:8px;
    white-space:nowrap;
    transition:.18s transform,.18s filter;
  }
  .nt-btn-primary:hover{ filter:brightness(1.05); transform:translateY(-1px); }

  /* Filters */
  .nt-filters{ display:flex; gap:10px; align-items:center; flex-wrap:wrap; width:100%; margin-top:-4px; }
  .nt-search{ position:relative; min-width:240px; flex:1 1 320px; max-width:560px; }
  .nt-search input{
    width:100%;
    border:1px solid var(--stroke);
    border-radius:12px;
    padding:12px 14px 12px 40px;
    background:var(--card);
    color:var(--ink);
    outline:none;
  }
  .nt-search input:focus{ box-shadow:0 0 0 4px var(--ring); border-color:transparent; }
  .nt-search svg{ position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--muted); }

  .nt-control{
    border:1px solid var(--stroke);
    border-radius:12px;
    padding:12px 14px;
    background:var(--card);
    color:var(--ink);
    min-width:170px;
  }
  .nt-control:focus{ outline:none; box-shadow:0 0 0 4px var(--ring); border-color:transparent; }

  /* Card */
  .nt-card{
    margin-top:14px;
    background:var(--card);
    border:1px solid var(--stroke);
    border-radius:var(--radius);
    box-shadow:var(--shadow);
    overflow:hidden;
  }
  .nt-head{
    padding:12px 16px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:10px;
    font-size:12px;
    color:var(--muted);
  }
  .nt-footer{
    padding:12px 16px;
    border-top:1px solid var(--stroke);
  }

  /* Table */
  .nt-table-scroll{ overflow-x:auto; }
  .nt-table{
    width:100%;
    border-collapse:separate;
    border-spacing:0;
    min-width:860px;
  }
  .nt-table th, .nt-table td{ padding:14px 16px; border-bottom:1px solid var(--stroke); vertical-align:top; }
  .nt-table thead th{
    background:linear-gradient(180deg, rgba(106,123,255,.06), rgba(34,211,238,.06));
    font-weight:900;
    text-align:left;
    white-space:nowrap;
  }
  .nt-table tbody tr:hover{ background: rgba(106,123,255,.06); }

  /* Snippet */
  .nt-desc{
    color:var(--muted);
    display:-webkit-box;
    -webkit-line-clamp:2;
    -webkit-box-orient:vertical;
    overflow:hidden;
    word-break:break-word;
    max-width:760px;
  }
  .nt-see-more{
    margin-top:6px;
    display:inline-flex;
    align-items:center;
    gap:.35rem;
    border:1px solid var(--stroke);
    background:transparent;
    color:var(--ink);
    padding:6px 10px;
    border-radius:10px;
    font-weight:900;
  }
  .nt-see-more:hover{ box-shadow:0 0 0 4px var(--ring); }

  /* Chips */
  .nt-chip{
    border-radius:999px;
    padding:6px 10px;
    font-weight:900;
    font-size:12px;
    display:inline-block;
    border:1px solid;
    white-space:nowrap;
  }
  .nt-chip-pub{ color:#065f46; background:rgba(16,185,129,.14); border-color:rgba(16,185,129,.35); }
  .nt-chip-draft{ color:#334155; background:rgba(148,163,184,.18); border-color:rgba(148,163,184,.35); }

  /* Action buttons: fix icon-only on mobile + keep aligned */
  .nt-actions{ display:flex; gap:8px; justify-content:flex-end; flex-wrap:wrap; }
  .nt-actions form{ margin:0; }
  .nt-actions .btn{ white-space:nowrap; }

  /* Mobile cards */
  .nt-cards{ display:none; padding:12px; }
  .nt-item{
    border:1px solid var(--stroke);
    border-radius:16px;
    background:var(--card);
    box-shadow:0 14px 26px rgba(2,6,23,.06);
    padding:14px;
  }
  .nt-top{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:12px;
  }
  .nt-item-title{ font-weight:900; font-size:16px; margin:0; }
  .nt-mini{ color:var(--muted); font-weight:700; font-size:12px; margin-top:6px; }

  .nt-hidden{ display:none !important; }
  .nt-empty{ text-align:center; color:var(--muted); padding:36px 12px; }

  /* Modal body wrap */
  .modal-body{ overflow-wrap:anywhere; word-break:break-word; }

  /* Responsive breakpoints */
  @media (max-width: 720px){
    .nt-table-scroll{ display:none; }
    .nt-cards{ display:block; }

    .nt-btn-primary{ width:100%; justify-content:center; }
    .nt-search{ max-width:none; flex:1 1 100%; }
    .nt-control{ flex:1 1 160px; min-width:160px; }

    .nt-actions{ display:grid; gap:8px; justify-content:stretch; }
    .nt-actions .btn{ width:100%; }
  }
</style>
@endpush

<div class="nt-page">
  <div class="nt-wrap">

    {{-- Header --}}
    <div class="nt-bar">
      <div>
        <h1 class="nt-title"><span>Notifications</span></h1>
        <div class="nt-subtle">{{ $total }} total</div>
      </div>

      <a href="{{ route('admin.notifications.create') }}" class="nt-btn-primary">
        <i class="bi bi-plus-lg"></i>
        <span>New Notification</span>
      </a>
    </div>

    {{-- Filters --}}
    <div class="nt-filters">
      <div class="nt-search">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path d="m21 21-4.3-4.3M16 10.5A5.5 5.5 0 1 1 5 10.5a5.5 5.5 0 0 1 11 0Z"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <input id="ntSearch" type="search" placeholder="Search by title or body…">
      </div>

      <select id="ntStatus" class="nt-control">
        <option value="">All statuses</option>
        <option value="published">Published</option>
        <option value="draft">Draft</option>
      </select>
    </div>

    {{-- Flash --}}
    @if(session('success'))
      <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif

    <div class="nt-card">
      <div class="nt-head">
        <span>Notifications list</span>
        <span>Search + filter updates instantly.</span>
      </div>

      {{-- ✅ Desktop/Tablet table --}}
      <div class="nt-table-scroll">
        <table class="nt-table">
          <thead>
            <tr>
              <th>Title & Snippet</th>
              <th>Published</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>

          <tbody id="ntBodyTable">
            @forelse($notifications as $n)
              @php
                $isPublished = !is_null($n->published_at);
                $status      = $isPublished ? 'published' : 'draft';

                $plain = trim(preg_replace("/\r\n|\r|\n/", "\n", strip_tags($n->body ?? '')));
                $snippet = Str::limit($plain, $TRUNC);

                $needsMore = Str::length($plain) > $TRUNC
                             || str_word_count($plain) > 18
                             || Str::contains($plain, "\n");

                // For fast search (include title + body)
                $search = strtolower(($n->title ?? '').' '.$plain);
              @endphp

              <tr class="nt-row" data-status="{{ $status }}" data-search="{{ $search }}">
                <td>
                  <div class="fw-bold">{{ $n->title }}</div>
                  <div class="nt-desc">{{ $snippet }}</div>

                  @if($needsMore)
                    <button type="button"
                            class="nt-see-more"
                            data-bs-toggle="modal"
                            data-bs-target="#ntModal{{ $n->id }}">
                      See more
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                      </svg>
                    </button>
                  @endif
                </td>

                <td>
                  @if($isPublished)
                    <span class="nt-chip nt-chip-pub">Published</span>
                    <div class="small nt-subtle mt-1">{{ $n->published_at->diffForHumans() }}</div>
                  @else
                    <span class="nt-chip nt-chip-draft">Draft</span>
                  @endif
                </td>

                <td class="text-end">
                  <div class="nt-actions">
                    @if(!$isPublished)
                      <form action="{{ route('admin.notifications.publish', $n) }}" method="POST">
                        @csrf
                        <button class="btn btn-success btn-sm">
                          <i class="bi bi-megaphone"></i> Publish
                        </button>
                      </form>
                    @endif

                    <form action="{{ route('admin.notifications.destroy', $n) }}"
                          method="POST"
                          onsubmit="return confirm('Delete this notification?')">
                      @csrf @method('DELETE')
                      <button class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-trash"></i>
                        <span class="d-none d-md-inline">Delete</span>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>

              {{-- Modal with full body --}}
              <div class="modal fade" id="ntModal{{ $n->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">{{ $n->title }}</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" style="white-space:pre-wrap;">
                      <div class="small nt-subtle mb-2">
                        <strong>Status:</strong> {{ $isPublished ? 'Published' : 'Draft' }}
                        @if($isPublished)
                          &nbsp;•&nbsp;<strong>When:</strong> {{ $n->published_at->format('Y-m-d H:i') }}
                        @endif
                      </div>
                      {!! nl2br(e($n->body)) !!}
                    </div>
                  </div>
                </div>
              </div>
            @empty
              <tr><td colspan="3" class="nt-empty">No notifications yet.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- ✅ Mobile cards --}}
      <div class="nt-cards" id="ntCards">
        @forelse($notifications as $n)
          @php
            $isPublished = !is_null($n->published_at);
            $status      = $isPublished ? 'published' : 'draft';

            $plain = trim(preg_replace("/\r\n|\r|\n/", "\n", strip_tags($n->body ?? '')));
            $snippet = Str::limit($plain, $TRUNC);

            $needsMore = Str::length($plain) > $TRUNC
                         || str_word_count($plain) > 18
                         || Str::contains($plain, "\n");

            $search = strtolower(($n->title ?? '').' '.$plain);
          @endphp

          <div class="nt-item nt-row" data-status="{{ $status }}" data-search="{{ $search }}">
            <div class="nt-top">
              <div style="min-width:0;">
                <p class="nt-item-title mb-1">{{ $n->title }}</p>
                <div class="nt-desc">{{ $snippet }}</div>

                @if($needsMore)
                  <button type="button"
                          class="nt-see-more"
                          data-bs-toggle="modal"
                          data-bs-target="#ntModal{{ $n->id }}">
                    See more
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                      <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </button>
                @endif
              </div>

              <div class="text-end">
                @if($isPublished)
                  <span class="nt-chip nt-chip-pub">Published</span>
                @else
                  <span class="nt-chip nt-chip-draft">Draft</span>
                @endif
              </div>
            </div>

            <div class="nt-mini">
              @if($isPublished)
                Published {{ $n->published_at->diffForHumans() }}
              @else
                Not published yet
              @endif
            </div>

            <div class="nt-actions mt-3">
              @if(!$isPublished)
                <form action="{{ route('admin.notifications.publish', $n) }}" method="POST">
                  @csrf
                  <button class="btn btn-success btn-sm">
                    <i class="bi bi-megaphone"></i> Publish
                  </button>
                </form>
              @endif

              <form action="{{ route('admin.notifications.destroy', $n) }}"
                    method="POST"
                    onsubmit="return confirm('Delete this notification?')">
                @csrf @method('DELETE')
                <button class="btn btn-outline-danger btn-sm">
                  <i class="bi bi-trash"></i> Delete
                </button>
              </form>
            </div>
          </div>
        @empty
          <div class="nt-empty">No notifications yet.</div>
        @endforelse
      </div>

      @if(is_object($notifications) && method_exists($notifications,'hasPages') && $notifications->hasPages())
        <div class="nt-footer">
          {{ $notifications->links() }}
        </div>
      @endif
    </div>

  </div>
</div>

<script>
(function(){
  const q   = document.getElementById('ntSearch');
  const sel = document.getElementById('ntStatus');

  function nodes(){
    // Both table rows + mobile cards have .nt-row
    return Array.from(document.querySelectorAll('.nt-row'));
  }

  function apply(){
    const term = (q.value || '').trim().toLowerCase();
    const st   = (sel.value || '').trim().toLowerCase();

    nodes().forEach(el => {
      const hay = (el.getAttribute('data-search') || el.textContent || '').toLowerCase();
      const rst = (el.getAttribute('data-status') || '').toLowerCase();

      const okText = !term || hay.includes(term);
      const okSt   = !st || rst === st;

      el.classList.toggle('nt-hidden', !(okText && okSt));
    });
  }

  q.addEventListener('input', apply, { passive:true });
  sel.addEventListener('change', apply, { passive:true });

  // Initial
  apply();
})();
</script>
@endsection
