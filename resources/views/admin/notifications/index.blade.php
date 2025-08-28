@extends('layouts.app')

@section('content')
@php
  use Illuminate\Support\Str;

  $total = (is_object($notifications) && method_exists($notifications,'total'))
            ? $notifications->total()
            : (is_countable($notifications) ? count($notifications) : 0);

  // Inline snippet target
  $TRUNC = 120;
@endphp

<style>
  :root{
    --bg:#f7f9ff; --ink:#0b1020; --muted:#6b7280; --card:#ffffff; --stroke:rgba(15,23,42,.10);
    --brand1:#6a7bff; --brand2:#22d3ee; --ring:rgba(106,123,255,.28); --radius:18px;
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

  .title{ font-size: clamp(28px,5.4vw,54px); font-weight:900; line-height:1.05; margin:0; }
  .title span{ background:linear-gradient(90deg,var(--brand1),var(--brand2)); -webkit-background-clip:text; background-clip:text; color:transparent; }
  .subtle{ color:var(--muted); font-weight:600; }

  .bar{ display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; margin-bottom:14px; }

  .btn-primaryx{
    border:0; border-radius:12px; padding:10px 16px; font-weight:900; color:#fff;
    background:linear-gradient(90deg,var(--brand1),var(--brand2));
    box-shadow:0 12px 28px rgba(106,123,255,.35);
    text-decoration:none;
  }
  .btn-primaryx:hover{ filter:brightness(1.05); transform:translateY(-1px); }

  .filters{ display:flex; gap:10px; align-items:center; flex-wrap:wrap; width:100%; }
  .search{ position:relative; min-width:240px; flex:1 1 320px; max-width:520px; }
  .search input{
    width:100%; border:1px solid var(--stroke); border-radius:12px; padding:12px 14px 12px 40px;
    background:var(--card); color:var(--ink); outline:none;
  }
  .search input:focus{ box-shadow:0 0 0 4px var(--ring); border-color:transparent; }
  .search svg{ position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--muted); }
  .control{ border:1px solid var(--stroke); border-radius:12px; padding:12px 14px; background:var(--card); color:var(--ink); }
  .control:focus{ outline:none; box-shadow:0 0 0 4px var(--ring); border-color:transparent; }

  .cardx{ background:var(--card); border:1px solid var(--stroke); border-radius:var(--radius); box-shadow:0 20px 55px rgba(2,6,23,.08); }
  .cardx .body{ padding:0; }
  .cardx .footer{ padding:12px 16px; border-top:1px solid var(--stroke); }

  .data-table{ width:100%; border-collapse:separate; border-spacing:0; }
  .data-table th, .data-table td{ padding:14px 16px; border-bottom:1px solid var(--stroke); vertical-align:top; }
  .data-table thead th{ background:linear-gradient(180deg, rgba(106,123,255,.06), rgba(34,211,238,.06)); font-weight:800; text-align:left; }
  .data-table tbody tr:hover{ background: rgba(106,123,255,.06); }

  /* Snippet: always clamp to two lines for nice rows */
  .desc{
    color:var(--muted);
    display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;
    max-width:760px;
    word-break:break-word;
  }
  .see-more{
    margin-top:6px; display:inline-flex; align-items:center; gap:.35rem;
    border:1px solid var(--stroke); background:transparent; color:var(--ink);
    padding:6px 10px; border-radius:10px; font-weight:800;
  }
  .see-more:hover{ box-shadow:0 0 0 4px var(--ring); }

  .chip{ border-radius:999px; padding:6px 10px; font-weight:900; font-size:12px; display:inline-block; border:1px solid; }
  .chip-pub{ color:#065f46; background:rgba(16,185,129,.14); border-color:rgba(16,185,129,.35); }
  .chip-draft{ color:#334155; background:rgba(148,163,184,.18); border-color:rgba(148,163,184,.35); }

  .actions .btn{ margin:2px 0; }
  @media (max-width: 720px){ .actions .btn{ width:100%; } }

  .empty{ text-align:center; color:var(--muted); padding:36px 12px; }
  .hidden{ display:none !important; }

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
    .desc{ max-width:100%; }
  }
</style>

<div class="page">
  <div class="wrap">

    {{-- Header --}}
    <div class="bar">
      <div>
        <h1 class="title"><span>Notifications</span></h1>
        <div class="subtle">{{ $total }} total</div>
      </div>
      <a href="{{ route('admin.notifications.create') }}" class="btn-primaryx">+ New Notification</a>
    </div>

    {{-- Filters --}}
    <div class="bar" style="margin-top:-4px;">
      <div class="filters">
        <div class="search">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
            <path d="m21 21-4.3-4.3M16 10.5A5.5 5.5 0 1 1 5 10.5a5.5 5.5 0 0 1 11 0Z"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          <input id="ntSearch" type="search" placeholder="Search by title or body…">
        </div>
        <select id="ntStatus" class="control" style="min-width:160px;">
          <option value="">All statuses</option>
          <option value="published">Published</option>
          <option value="draft">Draft</option>
        </select>
      </div>
    </div>

    {{-- Flash --}}
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Table / Cards --}}
    <div class="cardx">
      <div class="body">
        <table class="data-table">
          <thead>
            <tr>
              <th>Title & Snippet</th>
              <th>Published</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody id="ntBody">
            @forelse($notifications as $n)
              @php
                $isPublished = !is_null($n->published_at);
                $status      = $isPublished ? 'published' : 'draft';

                // Prepare safe plain body
                $plain = trim(preg_replace("/\r\n|\r|\n/", "\n", strip_tags($n->body ?? '')));
                $snippet = Str::limit($plain, $TRUNC);

                // Robust "needs more" detection: chars OR words OR line breaks
                $needsMore = Str::length($plain) > $TRUNC
                             || str_word_count($plain) > 18
                             || Str::contains($plain, "\n");
              @endphp

              <tr class="nt-row" data-status="{{ $status }}">
                <td data-label="Title & Snippet">
                  <div class="fw-bold">{{ $n->title }}</div>

                  <div class="desc">{{ $snippet }}</div>

                  @if($needsMore)
                    <button type="button"
                            class="see-more"
                            data-bs-toggle="modal"
                            data-bs-target="#ntModal{{ $n->id }}">
                      See more
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                        <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                      </svg>
                    </button>
                  @endif
                </td>

                <td data-label="Published">
                  @if($isPublished)
                    <span class="chip chip-pub">Published</span>
                    <div class="small subtle mt-1">{{ $n->published_at->diffForHumans() }}</div>
                  @else
                    <span class="chip chip-draft">Draft</span>
                  @endif
                </td>

                <td class="text-end" data-label="Actions">
                  <div class="actions d-grid d-md-inline-flex gap-2 justify-content-end">
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
                    <div class="modal-body" style="white-space:pre-wrap; word-wrap:break-word;">
                      <div class="small subtle mb-2">
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
              <tr><td colspan="3" class="empty">No notifications yet.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($notifications->hasPages())
        <div class="footer">
          {{ $notifications->links() }}
        </div>
      @endif
    </div>

  </div>
</div>

{{-- Live search + status filter --}}
<script>
  (function(){
    const q   = document.getElementById('ntSearch');
    const sel = document.getElementById('ntStatus');
    const rows = Array.from(document.querySelectorAll('#ntBody .nt-row'));

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
  })();
</script>
@endsection
