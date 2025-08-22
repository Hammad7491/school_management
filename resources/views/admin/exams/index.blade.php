@extends('layouts.app')

@section('content')
<style>
  /* —— Modern list with realtime search + responsive table→cards + dual actions —— */
  :root{
    --bg:#f7f9ff; --ink:#0b1020; --muted:#6b7280; --card:#ffffff; --stroke:rgba(15,23,42,.10);
    --brand1:#6a7bff; --brand2:#22d3ee; --danger:#e11d48; --ring:rgba(106,123,255,.28); --radius:18px;
  }
  @media (prefers-color-scheme: dark){
    :root{ --bg:#0b1020; --ink:#e6e9f5; --muted:#9aa3ba; --card:#0f1830; --stroke:rgba(255,255,255,.12); --ring:rgba(106,123,255,.45); }
  }

  .page{ min-height:100dvh; background:
    radial-gradient(800px 400px at -10% 0%, rgba(106,123,255,.12), transparent 60%),
    radial-gradient(700px 500px at 110% -10%, rgba(34,211,238,.12), transparent 60%),
    var(--bg); color:var(--ink); }
  .wrap{ max-width:1200px; margin:0 auto; padding:28px 14px 72px; }

  .title{ font-size: clamp(28px,5vw,56px); font-weight:900; line-height:1.05; margin:6px 0 6px; }
  .title span{ background: linear-gradient(90deg, var(--brand1), var(--brand2)); -webkit-background-clip:text; background-clip:text; color:transparent; }

  .bar{ display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:12px; flex-wrap:wrap; }
  .btn{ border:0; border-radius:12px; padding:12px 16px; font-weight:900; color:#fff; cursor:pointer; }
  .btn-primary{ background: linear-gradient(90deg, var(--brand1), var(--brand2)); box-shadow:0 12px 28px rgba(106,123,255,.35); }
  .btn-primary:hover{ filter:brightness(1.05); transform: translateY(-1px); }

  /* Search */
  .search{ display:flex; align-items:center; gap:8px; padding:10px 12px; border:1px solid var(--stroke); border-radius:12px; background:#fff; min-width:220px; max-width:420px; width:100%; }
  .search:focus-within{ border-color:#6a7bff; box-shadow:0 0 0 6px var(--ring); }
  .search input{ border:0; outline:0; width:100%; background:transparent; color:inherit; }
  .search .icon{ width:18px; height:18px; opacity:.6; }
  .search .clear{ display:none; border:0; background:transparent; font-size:18px; line-height:1; cursor:pointer; color:#6b7280; }

  .toast{ padding:12px 14px; border-radius:12px; border:1px solid; display:flex; gap:.6rem; align-items:flex-start; margin-bottom:12px; }
  .toast-success{ background:#ecfdf5; border-color:#a7f3d0; color:#065f46; }
  .toast-danger{ background:#fef2f2; border-color:#fecaca; color:#7f1d1d; }

  .card{ background:var(--card); border:1px solid var(--stroke); border-radius:var(--radius); box-shadow:0 20px 55px rgba(2,6,23,.08); }
  .card-body{ padding:0; }

  .scroll-x{ overflow:auto; -webkit-overflow-scrolling: touch; border-radius: calc(var(--radius) - 2px); }
  .tablex{ width:100%; border-collapse:separate; border-spacing:0; min-width:880px; }
  .tablex th, .tablex td{ padding:14px 16px; border-bottom:1px solid var(--stroke); vertical-align: middle; }
  .tablex thead th{ background:linear-gradient(180deg, rgba(106,123,255,.06), rgba(34,211,238,.06)); font-weight:800; text-align:left; position:sticky; top:0; z-index:1; }
  .tablex tbody tr:hover{ background: rgba(106,123,255,.06); }
  .ta-right{ text-align:right; }
  .num{ font-feature-settings:"tnum" on, "lnum" on; }
  .text-muted{ color:var(--muted); }

  .btn-sm{ border:1px solid var(--stroke); background:transparent; color:var(--ink); padding:8px 12px; border-radius:10px; font-weight:800; }
  .btn-sm:hover{ box-shadow:0 0 0 4px var(--ring); }
  .btn-danger{ color:#fff; background:linear-gradient(90deg,#ff5b6a,#ff8db3); border:0; }
  .chip{ display:inline-block; font-weight:800; padding:6px 10px; border-radius:999px; border:1px solid var(--stroke); font-size:12px; }

  .menu{ position:relative; display:inline-block; }
  .menu-btn{ border:1px solid var(--stroke); background:transparent; border-radius:10px; padding:8px 12px; font-weight:800; cursor:pointer; }
  .menu-btn:focus{ outline:none; box-shadow:0 0 0 4px var(--ring); border-color:#6a7bff; }
  .menu-list{ position:absolute; right:0; top:calc(100% + 6px); min-width:160px; background:var(--card); border:1px solid var(--stroke); border-radius:12px; box-shadow:0 14px 30px rgba(2,6,23,.15); padding:6px; display:none; z-index:5; }
  .menu[data-open="true"] .menu-list{ display:block; }
  .menu-item{ display:flex; width:100%; gap:.5rem; align-items:center; padding:10px 10px; border-radius:8px; text-decoration:none; border:0; background:transparent; color:var(--ink); font-weight:700; cursor:pointer; }
  .menu-item:hover{ background:rgba(106,123,255,.08); }
  .menu-divider{ height:1px; background:var(--stroke); margin:6px 0; }

  .desk-actions{ display:none; align-items:center; gap:8px; justify-content:flex-end; }
  .menu.mobile-actions{ display:inline-block; }
  @media (min-width: 992px){
    .desk-actions{ display:inline-flex; }
    .menu.mobile-actions{ display:none !important; }
  }

  /* Cards on phones */
  @media (max-width: 640px){
    .scroll-x{ overflow: visible; }
    .tablex{ min-width: 0; border-spacing: 0 12px; }
    .tablex thead{ display:none; }
    .tablex tbody tr{ display:block; background:var(--card); border:1px solid var(--stroke); border-radius:14px; margin:0 0 12px; padding: 8px 12px; box-shadow:0 8px 18px rgba(2,6,23,.06); }
    .tablex tbody tr > td{ display:grid; grid-template-columns: 120px 1fr; gap:8px; border:0; padding:8px 0; text-align:left; }
    .tablex tbody tr > td:first-child{ padding-top:6px; }
    .tablex tbody tr > td:last-child{ padding-bottom:6px; }
    .tablex tbody tr > td::before{ content: attr(data-label); font-size:12px; color:var(--muted); font-weight:700; }
    .ta-right{ text-align:left; }
    .btn-sm{ padding:8px 10px; }
    .menu-list{ position:static; display:none; box-shadow:none; border:0; padding:0; margin-top:6px; }
    .menu[data-open="true"] .menu-list{ display:block; }
  }
</style>

<div class="page">
  <div class="wrap">

    <div class="bar">
      <div>
        <h1 class="title"><span>Exams</span></h1>
        <div id="resultMeta" style="color:var(--muted); font-size:12px;"><span id="resultCount">{{ $exams->count() }}</span> on this page</div>
      </div>
      <form class="search" role="search" onsubmit="return false">
        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <input id="q" type="search" placeholder="Search by class or course…" autocomplete="off" />
        <button type="button" id="clearBtn" class="clear" aria-label="Clear">×</button>
      </form>
      @can('create exams')
      <a class="btn btn-primary" href="{{ route('exams.create') }}">+ Add Exam</a>
      @endcan
    </div>

    @if(session('success'))
      <div class="toast toast-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="toast toast-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
      <div class="card-body">
        <div class="scroll-x">
          <table id="examsTable" class="tablex align-middle">
            <thead>
              <tr>
                <th style="width:6rem">#</th>
                <th>Class</th>
                <th>Course</th>
                <th>Comment</th>
                <th>File</th>
                <th class="ta-right" style="width:16rem">Actions</th>
              </tr>
            </thead>
            <tbody>
              @php $start = (is_object($exams) && method_exists($exams,'firstItem')) ? $exams->firstItem() : 1; @endphp
              @forelse($exams as $ex)
                @php
                  $fileUrl  = $ex->file_path ? asset('storage/'.$ex->file_path) : null;
                  $fileName = $ex->file_name ?: ($ex->file_path ? basename($ex->file_path) : null);
                  $ext      = $fileName ? strtoupper(pathinfo($fileName, PATHINFO_EXTENSION)) : null;
                @endphp
                <tr data-row="exam">
                  <td class="num text-muted" data-label="#"> {{ $start + $loop->index }} </td>
                  <td data-col="class"  data-label="Class">{{ $ex->schoolClass?->name ?? '—' }}</td>
                  <td data-col="course" data-label="Course">{{ $ex->course?->name ?? '—' }}</td>
                  <td data-label="Comment" class="text-truncate" style="max-width:420px">{{ $ex->comment ?? '—' }}</td>
                  <td data-label="File">
                    @if($fileUrl)
                      <span class="chip">{{ $ext ?: 'FILE' }}</span>
                      <a href="{{ $fileUrl }}" target="_blank" class="btn-sm">Open</a>
                      <a href="{{ route('exams.download', $ex->id) }}" class="btn-sm">Download</a>
                    @else
                      <span class="text-muted">—</span>
                    @endif
                  </td>
                  <td class="ta-right" data-label="Actions">
                    @canany(['edit exams','delete exams'])
                      <!-- Desktop inline -->
                      <div class="desk-actions">
                        @can('edit exams')
                          <a class="btn-sm" href="{{ route('exams.edit', $ex->id) }}">Edit</a>
                        @endcan
                        @can('delete exams')
                          <form action="{{ route('exams.destroy', $ex->id) }}" method="POST" style="display:inline"
                                onsubmit="return confirm('Delete this exam?');">
                            @csrf @method('DELETE')
                            <button class="btn-sm btn-danger" type="submit">Delete</button>
                          </form>
                        @endcan
                      </div>
                      <!-- Mobile menu -->
                      <div class="menu mobile-actions" data-open="false">
                        <button type="button" class="menu-btn js-menu-btn" aria-haspopup="true" aria-expanded="false">Actions ▾</button>
                        <div class="menu-list" role="menu">
                          @can('edit exams')
                            <a class="menu-item" href="{{ route('exams.edit', $ex->id) }}" role="menuitem">Edit</a>
                          @endcan
                          @can('delete exams')
                            <div class="menu-divider"></div>
                            <form action="{{ route('exams.destroy', $ex->id) }}" method="POST"
                                  onsubmit="return confirm('Delete this exam?');">
                              @csrf @method('DELETE')
                              <button class="menu-item btn-danger" type="submit" role="menuitem">Delete</button>
                            </form>
                          @endcan
                        </div>
                      </div>
                    @else
                      <span class="text-muted">—</span>
                    @endcanany
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="empty">No exams yet. <a href="{{ route('exams.create') }}" style="text-decoration:underline">Add your first exam</a>.</td>
                </tr>
              @endforelse

              @if($exams->count() > 0)
                <tr id="noSearchRow" style="display:none;">
                  <td colspan="6" class="empty">No matches for your search.</td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>
      </div>

      @if(is_object($exams) && method_exists($exams,'hasPages') && $exams->hasPages())
        <div style="padding:12px;">{{ $exams->links() }}</div>
      @endif
    </div>

  </div>
</div>

<script>
  // Realtime search (class & course names; also checks comment for convenience) + renumber visible rows
  (function(){
    const q = document.getElementById('q');
    const clearBtn = document.getElementById('clearBtn');
    const table = document.getElementById('examsTable');
    if(!q || !table) return;

    const rows = Array.from(table.tBodies[0].querySelectorAll('tr[data-row="exam"]'));
    const countEl = document.getElementById('resultCount');
    const noRow = document.getElementById('noSearchRow');
    const norm = (s) => (s || '').toString().toLowerCase().trim();

    function renumber(){
      let n = 1;
      rows.forEach(tr => {
        if(tr.style.display !== 'none'){
          const cell = tr.querySelector('td[data-label="#"]');
          if(cell) cell.textContent = n++;
        }
      });
    }

    function filter(){
      const term = norm(q.value);
      let visible = 0;
      rows.forEach(tr => {
        const klass   = tr.querySelector('[data-col="class"]').textContent;
        const course  = tr.querySelector('[data-col="course"]').textContent;
        const comment = tr.querySelector('[data-label="Comment"]').textContent;
        const joined  = [klass, course, comment].map(norm).join(' ');
        const match   = joined.includes(term);
        tr.style.display = match ? '' : 'none';
        if(match) visible++;
      });
      if(countEl) countEl.textContent = visible;
      if(noRow) noRow.style.display = visible ? 'none' : '';
      clearBtn.style.display = term ? 'inline-flex' : 'none';
      renumber();
    }

    q.addEventListener('input', filter);
    clearBtn.addEventListener('click', () => { q.value=''; filter(); q.focus(); });
    filter();
  })();

  // Dropdown menu logic (mobile)
  (function(){
    const menus = Array.from(document.querySelectorAll('.menu'));
    function closeAll(except){
      menus.forEach(m => { if(m !== except){ m.dataset.open = 'false'; const b=m.querySelector('.js-menu-btn'); if(b){ b.setAttribute('aria-expanded','false'); } } });
    }
    menus.forEach(menu => {
      const btn = menu.querySelector('.js-menu-btn');
      const list = menu.querySelector('.menu-list');
      if(!btn || !list) return;
      btn.addEventListener('click', (e) => {
        const isOpen = menu.dataset.open === 'true';
        closeAll(menu);
        menu.dataset.open = isOpen ? 'false' : 'true';
        btn.setAttribute('aria-expanded', menu.dataset.open);
        if(menu.dataset.open === 'true'){
          const first = list.querySelector('a,button');
          if(first) setTimeout(() => first.focus(), 10);
        }
        e.stopPropagation();
      });
    });
    document.addEventListener('click', () => closeAll());
    document.addEventListener('keydown', (e) => { if(e.key === 'Escape'){ closeAll(); } });
  })();
</script>
@endsection
