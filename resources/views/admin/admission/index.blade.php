{{-- resources/views/admin/admission/index.blade.php --}}
@extends('layouts.app')

@section('content')
<style>
  /* Same theme as courses page */
  :root{
    --bg:#f7f9ff; --ink:#0b1020; --muted:#6b7280; --card:#ffffff; --stroke:rgba(15,23,42,.10);
    --brand1:#6a7bff; --brand2:#22d3ee; --danger:#e11d48; --ring:rgba(106,123,255,.28); --radius:18px;
  }
  @media (prefers-color-scheme: dark){
    :root{ --bg:#0b1020; --ink:#e6e9f5; --muted:#9aa3ba; --card:#0f1830; --stroke:rgba(255,255,255,.12); --ring:rgba(106,123,255,.45); }
  }

  .page{ min-height:100dvh; background: radial-gradient(800px 400px at -10% 0%, rgba(106,123,255,.12), transparent 60%),
                                      radial-gradient(700px 500px at 110% -10%, rgba(34,211,238,.12), transparent 60%), var(--bg); color:var(--ink); }
  .wrap{ max-width:1200px; margin:0 auto; padding:28px 14px 72px; }

  .title{ font-size: clamp(28px,5vw,56px); font-weight:900; line-height:1.05; margin:6px 0 6px; }
  .title span{ background: linear-gradient(90deg, var(--brand1), var(--brand2)); -webkit-background-clip:text; background-clip:text; color:transparent; }

  .bar{ display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:12px; flex-wrap:wrap; }
  .search{ display:flex; align-items:center; gap:8px; padding:10px 12px; border:1px solid var(--stroke); border-radius:12px; background:#fff; min-width:220px; max-width:420px; width:100%; }
  .search:focus-within{ border-color:#6a7bff; box-shadow:0 0 0 6px var(--ring); }
  .search input{ border:0; outline:0; width:100%; background:transparent; color:inherit; }
  .search .icon{ width:18px; height:18px; opacity:.6; }
  .search .clear{ display:none; border:0; background:transparent; font-size:18px; line-height:1; cursor:pointer; color:#6b7280; }

  .toast{ padding:12px 14px; border-radius:12px; border:1px solid; display:flex; gap:.6rem; align-items:flex-start; margin-bottom:12px; }
  .toast-success{ background:#ecfdf5; border-color:#a7f3d0; color:#065f46; }
  @media (prefers-color-scheme: dark){ .toast-success{ background:rgba(16,185,129,.14); border-color:rgba(16,185,129,.45); color:#d1fae5; }
    .search{ background:rgba(255,255,255,.04); }
  }

  .card{ background:var(--card); border:1px solid var(--stroke); border-radius:var(--radius); box-shadow:0 20px 55px rgba(2,6,23,.08); }
  .card-body{ padding:0; }

  .scroll-x{ overflow:auto; -webkit-overflow-scrolling: touch; border-radius: calc(var(--radius) - 2px); }
  .tablex{ width:100%; border-collapse:separate; border-spacing:0; min-width:860px; }
  .tablex th, .tablex td{ padding:14px 16px; border-bottom:1px solid var(--stroke); vertical-align: middle; }
  .tablex thead th{ background:linear-gradient(180deg, rgba(106,123,255,.06), rgba(34,211,238,.06)); font-weight:800; text-align:left; position:sticky; top:0; z-index:1; }
  .tablex tbody tr:hover{ background: rgba(106,123,255,.06); }
  .ta-right{ text-align:right; }
  .num{ font-feature-settings:"tnum" on, "lnum" on; }

  .btn-sm{ border:1px solid var(--stroke); background:transparent; color:var(--ink); padding:8px 12px; border-radius:10px; font-weight:800; }
  .btn-sm:hover{ box-shadow:0 0 0 4px var(--ring); }
  .btn-danger{ color:#fff; background:linear-gradient(90deg,#ff5b6a,#ff8db3); border:0; }

  .empty{ text-align:center; color:var(--muted); padding:36px 12px; }

  /* Action menu (mobile) */
  .menu{ position:relative; display:inline-block; }
  .menu-btn{ border:1px solid var(--stroke); background:transparent; border-radius:10px; padding:8px 12px; font-weight:800; cursor:pointer; }
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

  /* Mobile cards */
  @media (max-width: 640px){
    .scroll-x{ overflow: visible; }
    .tablex{ min-width: 0; border-spacing: 0 12px; }
    .tablex thead{ display:none; }
    .tablex tbody tr{ display:block; background:var(--card); border:1px solid var(--stroke); border-radius:14px; margin:0 0 12px; padding: 8px 12px; box-shadow:0 8px 18px rgba(2,6,23,.06); }
    .tablex tbody tr > td{ display:grid; grid-template-columns: 120px 1fr; gap:8px; border:0; padding:8px 0; text-align:left; }
    .tablex tbody tr > td::before{ content: attr(data-label); font-size:12px; color:var(--muted); font-weight:700; }
    .ta-right{ text-align:left; }
  }
</style>

<div class="page">
  <div class="wrap">

    <div class="bar">
      <div>
        <h1 class="title"><span>Admissions</span></h1>
        <div id="resultMeta" style="color:var(--muted); font-size:12px;">
          <span id="resultCount">{{ $admissions->count() }}</span> on this page
        </div>
      </div>
      <form class="search" role="search" onsubmit="return false">
        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
        </svg>
        <input id="q" type="search" placeholder="Search by student name…" autocomplete="off" />
        <button type="button" id="clearBtn" class="clear" aria-label="Clear">×</button>
      </form>
    </div>

    @if(session('success'))
      <div class="toast toast-success">{{ session('success') }}</div>
    @endif

    <div class="card">
      <div class="card-body">
        <div class="scroll-x">
          <table id="admissionsTable" class="tablex align-middle">
            <thead>
              <tr>
                <th>#</th>
                <th>Student</th>
                <th>Gender</th>
                <th>Class</th>
                <th>Parent</th>
                <th>Contact</th>
                <th>Email</th>
                <th class="ta-right">Actions</th>
              </tr>
            </thead>
            <tbody>
              @php $start = (is_object($admissions) && method_exists($admissions,'firstItem')) ? $admissions->firstItem() : 1; @endphp
              @forelse($admissions as $admission)
                <tr data-row="admission">
                  <td class="num" data-label="#">{{ $start + $loop->index }}</td>
                  <td data-col="student" data-label="Student">{{ $admission->student_name }}</td>
                  <td data-label="Gender">{{ $admission->gender }}</td>
                  <td data-label="Class">{{ $admission->class }}</td>
                  <td data-label="Parent">{{ $admission->parent_name }}</td>
                  <td data-label="Contact">{{ $admission->parent_contact }}</td>
                  <td data-label="Email">{{ $admission->parent_email }}</td>
                  <td class="ta-right" data-label="Actions">
                    <div class="desk-actions">
                      <form action="{{ route('admissions.destroy', $admission->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this admission?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-sm btn-danger">Delete</button>
                      </form>
                    </div>
                    <div class="menu mobile-actions" data-open="false">
                      <button type="button" class="menu-btn js-menu-btn">Actions ▾</button>
                      <div class="menu-list">
                        <form action="{{ route('admissions.destroy', $admission->id) }}" method="POST" onsubmit="return confirm('Delete this admission?')">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="menu-item btn-danger">Delete</button>
                        </form>
                      </div>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="empty">No admissions found yet.</td>
                </tr>
              @endforelse

              @if($admissions->count() > 0)
                <tr id="noSearchRow" style="display:none;">
                  <td colspan="8" class="empty">No matches for your search.</td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>
      </div>
    </div>

    @if(is_object($admissions) && method_exists($admissions,'hasPages') && $admissions->hasPages())
      <div style="margin-top:14px;">{{ $admissions->links() }}</div>
    @endif

  </div>
</div>

<script>
(function(){
  const q = document.getElementById('q');
  const clearBtn = document.getElementById('clearBtn');
  const table = document.getElementById('admissionsTable');
  if(!q || !table) return;

  const rows = Array.from(table.querySelectorAll('tr[data-row="admission"]'));
  const countEl = document.getElementById('resultCount');
  const noRow = document.getElementById('noSearchRow');

  function filter(){
    const term = q.value.toLowerCase().trim();
    let visible = 0;
    rows.forEach(tr => {
      const name = tr.querySelector('[data-col="student"]').textContent.toLowerCase();
      const match = name.includes(term);
      tr.style.display = match ? '' : 'none';
      if(match) visible++;
    });
    countEl.textContent = visible;
    noRow.style.display = visible ? 'none' : '';
    clearBtn.style.display = term ? 'inline-flex' : 'none';
  }

  q.addEventListener('input', filter);
  clearBtn.addEventListener('click', () => { q.value=''; filter(); q.focus(); });
  filter();
})();

// Dropdown menu logic
(function(){
  const menus = document.querySelectorAll('.menu');
  function closeAll(except){
    menus.forEach(m => { if(m !== except) m.dataset.open = 'false'; });
  }
  menus.forEach(menu => {
    const btn = menu.querySelector('.js-menu-btn');
    btn.addEventListener('click', e => {
      const open = menu.dataset.open === 'true';
      closeAll(menu);
      menu.dataset.open = open ? 'false' : 'true';
      e.stopPropagation();
    });
  });
  document.addEventListener('click', () => closeAll());
})();
</script>
@endsection
