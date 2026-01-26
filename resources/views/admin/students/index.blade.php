{{-- resources/views/admin/students/index.blade.php --}}
@extends('layouts.app')

@push('styles')
<style>
  /* Scoped styles (won't break other layout buttons) */
  .students-page{
    --bg:#f7f9ff; --ink:#0b1020; --muted:#6b7280; --card:#ffffff;
    --stroke:rgba(15,23,42,.10); --brand1:#6a7bff; --brand2:#22d3ee;
    --danger:#e11d48; --radius:18px;
    --ok:#10b981; --bad:#ef4444;
    min-height:100vh;
    background:
      radial-gradient(800px 400px at -10% 0%, rgba(106,123,255,.12), transparent 60%),
      radial-gradient(700px 500px at 110% -10%, rgba(34,211,238,.12), transparent 60%),
      var(--bg);
    color:var(--ink);
  }
  @media (prefers-color-scheme: dark){
    .students-page{
      --bg:#020617; --ink:#e5e7eb; --muted:#9ca3af; --card:#020817;
      --stroke:rgba(148,163,184,.35);
    }
  }

  .students-wrap{
    max-width:1400px;
    margin:auto;
    padding:28px 14px 72px;
  }

  /* Header */
  .students-bar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:14px;
  }
  .students-title{
    font-size:clamp(28px,5vw,56px);
    font-weight:900;
    letter-spacing:-0.03em;
    margin:0;
  }
  .students-title span{
    background:linear-gradient(90deg,var(--brand1),var(--brand2));
    -webkit-background-clip:text;
    background-clip:text;
    color:transparent;
  }
  .students-subtitle{
    color:var(--muted);
    font-size:13px;
    margin-top:4px;
  }

  /* Buttons (scoped) */
  .st-btn{
    border:0;
    border-radius:12px;
    padding:12px 18px;
    font-weight:800;
    cursor:pointer;
    display:inline-flex;
    align-items:center;
    gap:6px;
    font-size:14px;
    text-decoration:none;
    white-space:nowrap;
  }
  .st-btn-primary{
    color:#fff;
    background:linear-gradient(90deg,var(--brand1),var(--brand2));
    box-shadow:0 14px 30px rgba(37,99,235,.35);
    transition:.18s transform,.18s box-shadow,.18s filter;
  }
  .st-btn-primary:hover{
    filter:brightness(1.05);
    transform:translateY(-1px);
    box-shadow:0 18px 50px rgba(37,99,235,.42);
  }

  /* Search */
  .search-wrap{
    flex:1 1 260px;
    display:flex;
    justify-content:center;
  }
  .search{
    display:flex;
    align-items:center;
    gap:8px;
    padding:9px 12px;
    border:1px solid var(--stroke);
    border-radius:999px;
    background:var(--card);
    max-width:420px;
    width:100%;
    box-shadow:0 8px 25px rgba(15,23,42,.06);
  }
  .search-input{
    border:none;
    outline:none;
    flex:1;
    font-size:14px;
    color:var(--ink);
    background:transparent;
  }
  .search-input::placeholder{ color:var(--muted); }
  .clear-btn{
    border:0;
    width:22px;
    height:22px;
    border-radius:999px;
    display:none;
    align-items:center;
    justify-content:center;
    font-size:15px;
    cursor:pointer;
    background:rgba(148,163,184,.25);
    color:var(--ink);
  }
  .icon-magnify{ font-size:14px; color:var(--muted); }

  /* Shell */
  .shell{
    margin-top:20px;
    border-radius:var(--radius);
    background:var(--card);
    border:1px solid var(--stroke);
    box-shadow:0 22px 60px rgba(15,23,42,.12);
    overflow:hidden;
  }
  .shell-head{
    padding:12px 18px 0;
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:10px;
    font-size:12px;
    color:var(--muted);
  }

  /* Table */
  .table-scroll{ overflow-x:auto; }
  .tablex{
    width:100%;
    min-width:1100px;
    border-collapse:separate;
    border-spacing:0;
    font-size:14px;
  }
  .tablex thead{
    background:linear-gradient(90deg,rgba(148,163,184,.15),rgba(209,213,219,.02));
  }
  .tablex th,
  .tablex td{
    padding:14px 16px;
    border-bottom:1px solid var(--stroke);
    white-space:nowrap;
  }
  .tablex th{
    text-align:left;
    font-size:12px;
    text-transform:uppercase;
    letter-spacing:.08em;
    color:var(--muted);
    font-weight:700;
  }
  .tablex tbody tr:hover{ background:rgba(15,23,42,.02); }

  .student-name{ font-weight:700; color:var(--ink); }
  .student-email{ color:var(--muted); font-size:13px; }

  /* Chips */
  .chip{
    font-size:12px;
    padding:6px 11px;
    border-radius:999px;
    font-weight:800;
    display:inline-block;
  }
  .chip-class{ background:rgba(106,123,255,.15); color:#4338ca; }
  .chip-course{ background:rgba(34,211,238,.15); color:#0e7490; }
  .chip-ok{ background:rgba(16,185,129,.15); color:#047857; }
  .chip-bad{ background:rgba(239,68,68,.18); color:#b91c1c; }
  .chip-pending{ background:rgba(148,163,184,.2); color:#374151; }

  /* Small buttons */
  .btn-smx{
    padding:7px 12px;
    border-radius:10px;
    border:1px solid var(--stroke);
    background:var(--card);
    font-weight:800;
    font-size:12px;
    cursor:pointer;
    text-decoration:none;
    display:inline-flex;
    align-items:center;
    gap:6px;
    white-space:nowrap;
  }
  .btn-smx:hover{ background:rgba(148,163,184,.12); }
  .btn-dangerx{
    background:var(--bad);
    color:#fff;
    border:0;
  }
  .btn-dangerx:hover{ background:#dc2626; }

  .actions-cell{ display:flex; gap:8px; flex-wrap:wrap; }

  /* Pagination */
  .pagination-wrap{
    padding:12px 18px 16px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:10px;
    font-size:12px;
    color:var(--muted);
  }

  .empty-state{
    padding:28px 18px;
    text-align:center;
    font-size:14px;
    color:var(--muted);
  }

  /* MOBILE CARDS */
  .card-list{ display:flex; flex-direction:column; gap:12px; padding:14px; }
  .st-card{
    border:1px solid var(--stroke);
    border-radius:16px;
    background:var(--card);
    padding:14px;
    box-shadow:0 10px 25px rgba(15,23,42,.06);
  }
  .st-top{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:12px;
  }
  .st-main{ min-width:0; }
  .st-meta{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:10px 12px;
    margin-top:12px;
  }
  .meta-label{
    font-size:11px;
    color:var(--muted);
    text-transform:uppercase;
    letter-spacing:.08em;
    font-weight:800;
    margin-bottom:4px;
  }
  .truncate-1{
    display:block;
    max-width: 100%;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
  }

  /* Responsive tweaks */
  @media (max-width: 768px){
    .students-wrap{ padding-inline:10px; }
    .students-bar{ align-items:flex-start; }
    .search-wrap{ order:3; width:100%; }
    .search{ max-width:none; }
    .st-btn{ width:100%; justify-content:center; }
    .shell{ margin-top:16px; border-radius:16px; }
    .tablex{ min-width:900px; }
    .tablex th, .tablex td{ padding:10px 12px; font-size:13px; }
    .st-meta{ grid-template-columns:1fr; }
  }
  @media (max-width:480px){
    .students-title{ font-size:30px; }
  }
</style>
@endpush

@section('content')
<div class="students-page">
  <div class="students-wrap">

    {{-- Header --}}
    <div class="students-bar">
      <div>
        <h1 class="students-title"><span>Students</span></h1>
        <div class="students-subtitle">
          Showing <span id="resultCount">{{ $students->count() }}</span> students on this page
        </div>
      </div>

      <div class="search-wrap">
        <form class="search" onsubmit="return false;">
          <span class="icon-magnify">🔍</span>
          <input id="q" type="search" class="search-input" placeholder="Search by name or email…" />
          <button type="button" id="clearBtn" class="clear-btn" aria-label="Clear search">×</button>
        </form>
      </div>

      @can('create students')
        <a href="{{ route('students.create') }}" class="st-btn st-btn-primary">+ Add Student</a>
      @endcan
    </div>

    @if(session('success'))
      <div class="mt-3" style="border-radius:12px;padding:12px 18px;background:rgba(16,185,129,.15);color:#047857;border:1px solid rgba(16,185,129,.3);">
        {{ session('success') }}
      </div>
    @endif

    <div class="shell mt-3">
      <div class="shell-head">
        <span>Students list</span>
        <span>Use the search box above to quickly filter records.</span>
      </div>

      @php $start = $students->firstItem() ?? 1; @endphp

      {{-- ✅ MOBILE VIEW (cards) --}}
      <div class="d-lg-none">
        @if($students->count() === 0)
          <div class="empty-state">
            <div style="font-size:48px;margin-bottom:12px;">📚</div>
            <div style="font-size:16px;font-weight:800;margin-bottom:6px;">No students found</div>
            <div>Click "Add Student" to create the first one.</div>
          </div>
        @else
          <div class="card-list" id="studentsCards">
            @foreach($students as $s)
              <div class="st-card" data-row="student">
                <div class="st-top">
                  <div class="st-main">
                    <div class="students-subtitle">#{{ $start + $loop->index }} • Reg: <strong>{{ $s->reg_no }}</strong></div>

                    <div class="student-name mt-1" data-col="name">
                      {{ $s->name }}
                    </div>

                    <div class="student-email truncate-1" data-col="email">
                      {{ $s->email }}
                    </div>

                    <div class="mt-2 d-flex flex-wrap gap-2">
                      @if($s->schoolClass)
                        <span class="chip chip-class">{{ $s->schoolClass->name }}</span>
                      @endif

                      @if($s->course)
                        <span class="chip chip-course">{{ $s->course->name }}</span>
                      @endif

                      @if ($s->status === 1)
                        <span class="chip chip-ok">Approved</span>
                      @elseif ($s->status === 0)
                        <span class="chip chip-bad">Rejected</span>
                      @else
                        <span class="chip chip-pending">Pending</span>
                      @endif
                    </div>
                  </div>

                  <div class="actions-cell" style="justify-content:flex-end;">
                    @can('edit students')
                      <a href="{{ route('students.edit', $s->id) }}" class="btn-smx" title="Edit">✏️ Edit</a>
                    @endcan

                    @can('delete students')
                      <form action="{{ route('students.destroy', $s->id) }}" method="POST" class="m-0"
                            onsubmit="return confirm('Delete student {{ $s->name }}?');">
                        @csrf @method('DELETE')
                        <button class="btn-smx btn-dangerx" type="submit" title="Delete">🗑️ Delete</button>
                      </form>
                    @endcan
                  </div>
                </div>

                <div class="st-meta">
                  <div>
                    <div class="meta-label">Photo</div>
                    @if($s->profile_image_path)
                      <a class="btn-smx" href="{{ route('students.photo.download', $s->id) }}">📥 Download Photo</a>
                    @else
                      <span style="color:var(--muted);">—</span>
                    @endif
                  </div>

                  <div>
                    <div class="meta-label">B-Form</div>
                    @if($s->b_form_image_path)
                      <a class="btn-smx" href="{{ route('students.bform.download', $s->id) }}">📥 Download B-Form</a>
                    @else
                      <span style="color:var(--muted);">—</span>
                    @endif
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>

      {{-- ✅ DESKTOP VIEW (table) --}}
      <div class="d-none d-lg-block">
        <div class="table-scroll">
          <table class="tablex" id="studentsTable">
            <thead>
              <tr>
                <th>#</th>
                <th>Reg #</th>
                <th>Student Name</th>
                <th>Email</th>
                <th>Class</th>
                <th>Course</th>
                <th>Photo</th>
                <th>B-Form</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>

            <tbody>
              @forelse($students as $s)
                <tr data-row="student">
                  <td>{{ $start + $loop->index }}</td>
                  <td><strong>{{ $s->reg_no }}</strong></td>

                  <td data-col="name">
                    <div class="student-name">{{ $s->name }}</div>
                  </td>

                  <td data-col="email">
                    <div class="student-email">{{ $s->email }}</div>
                  </td>

                  <td>
                    @if($s->schoolClass)
                      <span class="chip chip-class">{{ $s->schoolClass->name }}</span>
                    @else
                      <span style="color:var(--muted);">—</span>
                    @endif
                  </td>

                  <td>
                    @if($s->course)
                      <span class="chip chip-course">{{ $s->course->name }}</span>
                    @else
                      <span style="color:var(--muted);">—</span>
                    @endif
                  </td>

                  <td>
                    @if($s->profile_image_path)
                      <a class="btn-smx" href="{{ route('students.photo.download', $s->id) }}">📥 Photo</a>
                    @else
                      <span style="color:var(--muted);">—</span>
                    @endif
                  </td>

                  <td>
                    @if($s->b_form_image_path)
                      <a class="btn-smx" href="{{ route('students.bform.download', $s->id) }}">📥 B-Form</a>
                    @else
                      <span style="color:var(--muted);">—</span>
                    @endif
                  </td>

                  <td>
                    @if ($s->status === 1)
                      <span class="chip chip-ok">Approved</span>
                    @elseif ($s->status === 0)
                      <span class="chip chip-bad">Rejected</span>
                    @else
                      <span class="chip chip-pending">Pending</span>
                    @endif
                  </td>

                  <td>
                    <div class="actions-cell">
                      @can('edit students')
                        <a href="{{ route('students.edit', $s->id) }}" class="btn-smx" title="Edit">✏️ Edit</a>
                      @endcan

                      @can('delete students')
                        <form action="{{ route('students.destroy', $s->id) }}" method="POST" class="m-0"
                              onsubmit="return confirm('Delete student {{ $s->name }}?');">
                          @csrf @method('DELETE')
                          <button class="btn-smx btn-dangerx" type="submit" title="Delete">🗑️ Delete</button>
                        </form>
                      @endcan
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="10" class="empty-state">
                    <div style="font-size:48px;margin-bottom:12px;">📚</div>
                    <div style="font-size:16px;font-weight:800;margin-bottom:6px;">No students found</div>
                    <div>Click "Add Student" to create the first one.</div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <div class="pagination-wrap">
        <div>Page {{ $students->currentPage() }} of {{ $students->lastPage() }}</div>
        <div>{{ $students->links() }}</div>
      </div>
    </div>

  </div>
</div>

<script>
(function(){
  const q        = document.getElementById('q');
  const clearBtn = document.getElementById('clearBtn');
  const countEl  = document.getElementById('resultCount');

  function norm(v){ return (v || "").toLowerCase().trim(); }

  function getAllRows(){
    // table rows + mobile cards both have data-row="student"
    return [...document.querySelectorAll('[data-row="student"]')];
  }

  function filter(){
    const term = norm(q.value);
    const rows = getAllRows();
    let visible = 0;

    rows.forEach(el => {
      const nameEl  = el.querySelector('[data-col="name"]');
      const emailEl = el.querySelector('[data-col="email"]');

      const name  = nameEl ? norm(nameEl.textContent) : '';
      const email = emailEl ? norm(emailEl.textContent) : '';

      const match = !term || name.includes(term) || email.includes(term);
      el.style.display = match ? "" : "none";
      if(match) visible++;
    });

    countEl.textContent = visible;
    clearBtn.style.display = term ? "flex" : "none";
  }

  q.addEventListener('input', filter);
  clearBtn.addEventListener('click', () => {
    q.value = '';
    filter();
  });

  // initial
  filter();
})();
</script>
@endsection
