@extends('students.layouts.app')

@section('content')
@php
  use Illuminate\Support\Str;
  use Carbon\Carbon;

  $total = (is_object($notifications) && method_exists($notifications,'total'))
            ? $notifications->total()
            : (is_countable($notifications) ? count($notifications) : 0);

  $now   = Carbon::now();
  $TRUNC = 180;  // inline snippet target
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

  .bar{ display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; margin-bottom:12px; }
  .title{ font-size: clamp(26px,5vw,42px); font-weight:900; margin:0; line-height:1.06; }
  .title span{ background:linear-gradient(90deg,var(--brand1),var(--brand2)); -webkit-background-clip:text; background-clip:text; color:transparent; }
  .subtle{ color:var(--muted); font-weight:600; }

  .btn-back{ display:inline-flex; align-items:center; gap:.5rem; padding:10px 12px; border-radius:12px;
             border:1px solid var(--stroke); color:var(--ink); text-decoration:none; font-weight:800; background:var(--card); }
  .btn-back:hover{ box-shadow:0 0 0 4px var(--ring); }

  .search{ position:relative; min-width:240px; flex:1 1 320px; max-width:520px; }
  .search input{
    width:100%; border:1px solid var(--stroke); border-radius:12px; padding:12px 14px 12px 40px;
    background:var(--card); color:var(--ink); outline:none;
  }
  .search input:focus{ box-shadow:0 0 0 4px var(--ring); border-color:transparent; }
  .search svg{ position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--muted); }

  .cardx{ background:var(--card); border:1px solid var(--stroke); border-radius:var(--radius); box-shadow:0 18px 45px rgba(2,6,23,.08); }
  .cardx .body{ padding:0; }
  .cardx .footer{ padding:12px 16px; border-top:1px solid var(--stroke); }

  .noti{ display:flex; gap:12px; align-items:flex-start; padding:16px; border-bottom:1px solid var(--stroke); }
  .noti:last-child{ border-bottom:0; }
  .noti-icon{
    flex:0 0 42px; height:42px; width:42px; border-radius:12px; display:flex; align-items:center; justify-content:center;
    background:linear-gradient(135deg, rgba(106,123,255,.18), rgba(34,211,238,.18));
  }
  .noti-main{ flex:1 1 auto; min-width:0; }
  .noti-title{ font-weight:900; margin:0 0 6px; line-height:1.2; word-break:break-word; }

  /* Clamp to 2 lines; we’ll show a See more button if content is long */
  .noti-text{
    margin:0; color:var(--muted);
    display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;
    word-break:break-word;
  }

  .meta-row{ display:flex; gap:8px; align-items:center; flex-wrap:wrap; margin-top:8px; }
  .pill{ padding:6px 10px; border-radius:999px; border:1px solid var(--stroke); background:var(--card); font-weight:800; font-size:12px; color:var(--muted); }
  .pill-new{ color:#065f46; background:rgba(16,185,129,.12); border-color:rgba(16,185,129,.35); }

  .see-more{
    margin-left:auto; display:inline-flex; align-items:center; gap:.35rem;
    border:1px solid var(--stroke); background:transparent; color:var(--ink);
    padding:6px 10px; border-radius:10px; font-weight:800;
  }
  .see-more:hover{ box-shadow:0 0 0 4px var(--ring); }

  @media (max-width: 640px){
    .noti{ padding:14px; }
    .noti-text{ font-size:14px; }
    .see-more{ width:100%; justify-content:center; }
  }

  .empty{ text-align:center; color:var(--muted); padding:36px 12px; }
  .hidden{ display:none !important; }
</style>

<div class="page">
  <div class="wrap">

    {{-- Header --}}
    <div class="bar">
      <div>
        <h1 class="title"><span>All</span> Notifications</h1>
        <div class="subtle">{{ $total }} on this page</div>
      </div>
      <a href="{{ route('student.dashboard') }}" class="btn-back" aria-label="Back to Dashboard">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="m12 19-7-7 7-7M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        Back to Dashboard
      </a>
    </div>

    {{-- Search --}}
    <div class="bar" style="margin-top:-6px;">
      <div class="search">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
          <path d="m21 21-4.3-4.3M16 10.5A5.5 5.5 0 1 1 5 10.5a5.5 5.5 0 0 1 11 0Z"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <input id="notiSearch" type="search" placeholder="Search notifications (title or text)…">
      </div>
    </div>

    {{-- List --}}
    <div class="cardx">
      <div class="body" id="notiList">
        @forelse($notifications as $n)
          @php
            $published = $n->published_at ?? $n->created_at;
            $isNew     = $published ? Carbon::parse($published)->greaterThan($now->copy()->subDays(7)) : false;
            $human     = $published ? Carbon::parse($published)->diffForHumans() : '—';

            // Plain body for detection
            $plain    = trim(preg_replace("/\r\n|\r|\n/", "\n", strip_tags($n->body ?? '')));
            $snippet  = Str::limit($plain, $TRUNC);
            $needsMore = Str::length($plain) > $TRUNC   // characters
                         || str_word_count($plain) > 28  // words
                         || Str::contains($plain, "\n"); // line breaks
          @endphp

          <article class="noti">
            <div class="noti-icon">
              <iconify-icon icon="mdi:bullhorn-outline" style="font-size:22px;"></iconify-icon>
            </div>

            <div class="noti-main">
              <h6 class="noti-title">{{ $n->title }}</h6>
              <p class="noti-text">{{ $snippet }}</p>

              <div class="meta-row">
                <span class="pill" title="{{ $published }}">{{ $human }}</span>
                @if($isNew)
                  <span class="pill pill-new">New</span>
                @endif>

                @if($needsMore)
                  <button type="button"
                          class="see-more ms-auto"
                          data-bs-toggle="modal"
                          data-bs-target="#notiModal{{ $n->id }}">
                    See more
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                      <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </button>
                @endif
              </div>
            </div>
          </article>

          {{-- Modal with full text --}}
          <div class="modal fade" id="notiModal{{ $n->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">{{ $n->title }}</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="white-space:pre-wrap; word-wrap:break-word;">
                  <div class="small text-muted mb-2">
                    <strong>Published:</strong>
                    {{ $published ? Carbon::parse($published)->format('Y-m-d H:i') : '—' }}
                  </div>
                  {!! nl2br(e($n->body)) !!}
                </div>
              </div>
            </div>
          </div>
        @empty
          <div class="empty">No notifications available.</div>
        @endforelse
      </div>

      @if($notifications->hasPages())
        <div class="footer">
          {{ $notifications->links() }}
        </div>
      @endif
    </div>

  </div>
</div>

<script>
  // Real-time search (client-side for current page)
  (function(){
    const input = document.getElementById('notiSearch');
    const list  = document.getElementById('notiList');
    const items = Array.from(list.querySelectorAll('.noti'));

    function filter(){
      const term = input.value.trim().toLowerCase();
      items.forEach(it => {
        const hay = it.textContent.toLowerCase();
        it.classList.toggle('hidden', term && !hay.includes(term));
      });
    }
    input.addEventListener('input', filter, { passive:true });
  })();
</script>
@endsection
