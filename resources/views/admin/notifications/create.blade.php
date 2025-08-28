@extends('layouts.app')

@section('content')
@php
  $oldTitle = old('title', '');
  $oldBody  = old('body', '');
@endphp

<style>
  :root{
    --bg:#f7f9ff; --ink:#0b1020; --muted:#6b7280; --card:#ffffff; --stroke:rgba(15,23,42,.10);
    --brand1:#6a7bff; --brand2:#22d3ee; --ring:rgba(106,123,255,.28);
    --radius:18px;
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

  .title{ font-size: clamp(28px,5.4vw,54px); font-weight:900; line-height:1.05; margin:0 0 8px; }
  .title span{ background:linear-gradient(90deg,var(--brand1),var(--brand2)); -webkit-background-clip:text; background-clip:text; color:transparent; }
  .subtle{ color:var(--muted); font-weight:600; }

  .bar{ display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; margin-bottom:16px; }
  .btn-primary{
    border:0; border-radius:12px; padding:10px 16px; font-weight:900; color:#fff;
    background:linear-gradient(90deg,var(--brand1),var(--brand2)); box-shadow:0 12px 28px rgba(106,123,255,.35);
  }
  .btn-primary:hover{ filter:brightness(1.05); transform:translateY(-1px); }
  .btn-soft{ border:1px solid var(--stroke); background:var(--card); color:var(--ink); border-radius:12px; padding:10px 14px; font-weight:800; }
  .btn-soft:hover{ box-shadow:0 0 0 4px var(--ring); }

  .grid{
    display:grid; gap:16px;
    grid-template-columns: 1fr;
  }
  @media (min-width: 992px){
    .grid{ grid-template-columns: 1fr 0.9fr; }
  }

  .cardx{ background:var(--card); border:1px solid var(--stroke); border-radius:var(--radius); box-shadow:0 20px 55px rgba(2,6,23,.08); }
  .cardx .body{ padding:18px; }
  .cardx .footer{ padding:12px 18px; border-top:1px solid var(--stroke); display:flex; gap:10px; flex-wrap:wrap; }

  /* Fields */
  .field{ margin-bottom:14px; }
  .label{ font-weight:800; margin-bottom:6px; display:flex; align-items:center; justify-content:space-between; gap:8px; }
  .hint{ font-size:12px; color:var(--muted); }

  .control{
    width:100%; border:1px solid var(--stroke); border-radius:12px; padding:12px 14px; background:var(--card); color:var(--ink);
    outline:none; transition:box-shadow .15s, border-color .15s;
  }
  textarea.control{ resize:vertical; min-height:160px; }
  .control:focus{ box-shadow:0 0 0 4px var(--ring); border-color:transparent; }

  .counter{ font-size:12px; color:var(--muted); }
  .counter.over{ color:#b91c1c; font-weight:800; }

  /* Preview */
  .preview-card .title{ font-size:22px; margin:0 0 6px; }
  .preview-card .meta{ font-size:12px; color:var(--muted); margin-bottom:8px; }
  .preview-card .bodytext{ white-space:pre-wrap; }

  /* Alerts tweak inside gradient bg */
  .alert{ border-radius:12px; }
</style>

<div class="page">
  <div class="wrap">

    <div class="bar">
      <div>
        <h1 class="title"><span>New</span> Notification</h1>
        <div class="subtle">Create an announcement for students & guardians.</div>
      </div>
      <a href="{{ route('admin.notifications.index') }}" class="btn-soft">← Back to list</a>
    </div>

    @if($errors->any())
      <div class="alert alert-danger">
        <strong>Fix the following:</strong>
        <ul class="mb-0">
          @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
      </div>
    @endif

    <div class="grid">
      {{-- Form --}}
      <div class="cardx">
        <form action="{{ route('admin.notifications.store') }}" method="POST" novalidate>
          @csrf
          <div class="body">

            {{-- Title --}}
            <div class="field">
              <label class="label">
                <span>Title <span class="text-danger">*</span></span>
                <span id="titleCounter" class="counter">0/200</span>
              </label>
              <input type="text"
                     id="titleInput"
                     name="title"
                     value="{{ $oldTitle }}"
                     maxlength="200"
                     class="control"
                     placeholder="Write a concise, clear title"
                     required>
              <div class="hint mt-1">Max 200 characters. Appears bold at the top of the notification.</div>
            </div>

            {{-- Body --}}
            <div class="field">
              <label class="label">
                <span>Body <span class="text-danger">*</span></span>
              </label>
              <textarea id="bodyInput"
                        name="body"
                        rows="6"
                        class="control"
                        placeholder="Type the details students should know…"
                        required>{{ $oldBody }}</textarea>
              <div class="hint mt-1">You can paste plain text. Line breaks are preserved.</div>
            </div>

            {{-- Publish now --}}
            <div class="field">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="publishNow" name="publish_now" value="1" {{ old('publish_now') ? 'checked' : '' }}>
                <label class="form-check-label" for="publishNow">Publish now</label>
              </div>
              <div class="hint mt-1" id="publishHint">If off, the notification will be saved as draft (unpublished).</div>
            </div>

          </div>

          <div class="footer">
            <button class="btn-primary" type="submit">Save Notification</button>
            <a href="{{ route('admin.notifications.index') }}" class="btn-soft">Cancel</a>
          </div>
        </form>
      </div>

      {{-- Live Preview (hidden on small screens until scrolled) --}}
      <div class="cardx preview-card">
        <div class="body">
          <div class="meta">Preview</div>
          <div id="prevTitle" class="title">Untitled notification</div>
          <div id="prevMeta" class="meta">Will be saved as draft</div>
          <div id="prevBody" class="bodytext subtle">Start typing to see a live preview…</div>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
  (function(){
    const titleMax = 200;
    const $title = document.getElementById('titleInput');
    const $body  = document.getElementById('bodyInput');
    const $count = document.getElementById('titleCounter');
    const $prevTitle = document.getElementById('prevTitle');
    const $prevBody  = document.getElementById('prevBody');
    const $publish   = document.getElementById('publishNow');
    const $prevMeta  = document.getElementById('prevMeta');
    const $publishHint = document.getElementById('publishHint');

    function updateTitleCount(){
      const len = ($title.value || '').length;
      $count.textContent = `${len}/${titleMax}`;
      $count.classList.toggle('over', len > titleMax);
    }

    function updatePreview(){
      const t = ($title.value || '').trim();
      const b = ($body.value || '').trim();

      $prevTitle.textContent = t || 'Untitled notification';
      $prevBody.textContent  = b || 'Start typing to see a live preview…';

      if ($publish.checked) {
        $prevMeta.textContent = 'Will be published immediately';
        $publishHint.textContent = 'This will be visible to students as soon as you save.';
      } else {
        $prevMeta.textContent = 'Will be saved as draft';
        $publishHint.textContent = 'If off, the notification will be saved as draft (unpublished).';
      }
    }

    // Auto-resize textarea
    function autoresize(){
      $body.style.height = 'auto';
      $body.style.height = Math.min($body.scrollHeight, 600) + 'px';
    }

    $title.addEventListener('input', () => { updateTitleCount(); updatePreview(); }, {passive:true});
    $body.addEventListener('input', () => { autoresize(); updatePreview(); }, {passive:true});
    $publish.addEventListener('change', updatePreview, {passive:true});

    // Initial
    updateTitleCount();
    autoresize();
    updatePreview();
  })();
</script>
@endsection
