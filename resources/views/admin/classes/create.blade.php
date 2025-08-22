@extends('layouts.app')

@section('content')
<style>
  /* —— Premium-simple aesthetic (no framework). Crisp type, gradient header, glass card, luxe focus. —— */
  :root{
    --bg: #f7f9ff;
    --ink: #0b1020;
    --muted: #6b7280;
    --card: #ffffff;
    --stroke: rgba(15,23,42,.10);
    --ring: rgba(99,102,241,.28);
    --brand1: #6a7bff; /* indigo */
    --brand2: #22d3ee; /* cyan */
    --accent: #ff8db3; /* soft pink */
    --danger: #e11d48;
    --success: #10b981;
    --radius-lg: 18px;
  }
  @media (prefers-color-scheme: dark){
    :root{ --bg:#0b1020; --ink:#e6e9f5; --muted:#94a3b8; --card:#0f1830; --stroke:rgba(255,255,255,.12); --ring: rgba(106,123,255,.45); }
  }

  /* Page background with elegant orbs */
  .page{ min-height:100dvh; background: radial-gradient(800px 400px at -10% 0%, rgba(106,123,255,.12), transparent 60%),
                                      radial-gradient(700px 500px at 110% -10%, rgba(34,211,238,.12), transparent 60%),
                                      var(--bg); color: var(--ink); }
  .wrap{ max-width: 980px; margin: 0 auto; padding: 28px 14px 72px; }

  /* Header */
  .title{ font-size: clamp(30px, 6vw, 64px); font-weight: 900; line-height:1.05; margin: 6px 0 10px; letter-spacing: .2px; }
  .title span{ background: linear-gradient(90deg, var(--brand1), var(--brand2)); -webkit-background-clip: text; background-clip: text; color: transparent; }
  .sub{ color: var(--muted); margin: 0 0 18px; font-size: clamp(14px, 1.4vw, 16px); }

  /* Card */
  .card{ position:relative; background: var(--card); border: 1px solid var(--stroke); border-radius: var(--radius-lg); padding: 22px; box-shadow: 0 20px 55px rgba(2,6,23,.08); }
  .card:before{ content:""; position:absolute; inset:0; border-radius:inherit; pointer-events:none; background: linear-gradient(180deg, rgba(106,123,255,.12), rgba(34,211,238,.08)); opacity:.25; }
  .card .inner{ position:relative; z-index:1; }

  /* Grid */
  .grid{ display:grid; grid-template-columns: 1fr; gap:16px; }
  @media (min-width: 760px){ .grid{ grid-template-columns: 1.15fr .85fr; } }

  /* Field */
  .label{ font-weight: 800; display:flex; align-items:center; gap:.35rem; margin-bottom: 8px; }
  .req{ color: var(--danger); font-weight:900; }
  .hint{ font-size:12px; color: var(--muted); margin-top:8px; }
  .field{ position:relative; }

  /* Inputs */
  .control{ position:relative; }
  .input, .select{ width:100%; padding: 14px 14px 14px 44px; border-radius: 14px; border:1px solid var(--stroke); background: #fff; color: inherit; outline: none; transition: .18s border-color, .18s box-shadow, .18s transform; }
  .select{ appearance:none; background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="%2399a2b3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>'); background-repeat:no-repeat; background-position: right 12px center; background-size: 18px; padding-right: 40px; }
  .input:focus, .select:focus{ border-color: var(--brand1); box-shadow: 0 0 0 6px var(--ring); }
  .input:invalid{ border-color: rgba(225,29,72,.6); }

  /* Icons inside fields */
  .icon{ position:absolute; left:12px; top:50%; transform: translateY(-50%); width:20px; height:20px; opacity:.55; }

  /* Group with addon */
  .group{ display:flex; align-items:stretch; }
  .addon{ padding: 0 12px; display:grid; place-items:center; border:1px solid var(--stroke); border-right:0; border-radius:14px 0 0 14px; color:#6b7280; background:#f7f9ff; font-weight:900; }
  .group .input{ border-radius:0 14px 14px 0; padding-left:14px; }

  /* Actions */
  .actions{ display:flex; flex-wrap: wrap; gap:12px; align-items:center; margin-top: 14px; }
  .btn{ position:relative; isolation:isolate; border:0; cursor:pointer; padding: 12px 18px; border-radius: 12px; color: #fff; font-weight:900; letter-spacing:.2px; }
  .btn-primary{ background: linear-gradient(90deg, var(--brand1), var(--brand2)); box-shadow: 0 12px 28px rgba(106,123,255,.35); }
  .btn-primary:hover{ filter: brightness(1.04); transform: translateY(-1px); }
  .btn-primary:active{ transform: translateY(0); }
  .btn-ghost{ background: transparent; color: var(--brand1); }
  .btn-ghost:hover{ text-decoration: underline; }

  /* Alerts */
  .toast{ padding:12px 14px; border-radius: 12px; border:1px solid; display:flex; gap:.6rem; align-items:flex-start; margin-bottom:12px; }
  .toast-success{ background:#ecfdf5; border-color:#a7f3d0; color:#065f46; }
  .toast-danger{ background:#fef2f2; border-color:#fecaca; color:#7f1d1d; }
  @media (prefers-color-scheme: dark){
    .input, .select{ background: rgba(255,255,255,.02); }
    .addon{ background: rgba(255,255,255,.04); color:#9aa3ba; }
    .toast-success{ background: rgba(16,185,129,.14); border-color: rgba(16,185,129,.45); color:#d1fae5; }
    .toast-danger{ background: rgba(225,29,72,.16); border-color: rgba(225,29,72,.45); color:#ffe4e6; }
  }
</style>

@php
  $isEdit   = isset($class);
  $options  = ['Play Group','Nursery','Prep','1','2','3','4','5','6','7','8','9','10'];
  $selected = old('name', $class->name ?? '');
@endphp

<div class="page">
  <div class="wrap">
    <h1 class="title"><span>{{ $isEdit ? 'Edit' : 'Add New' }}</span> Class</h1>
    <p class="sub"> <strong></strong></p>

    @if(session('success'))
      <div class="toast toast-success">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="m5 13 4 4L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        <div>{{ session('success') }}</div>
      </div>
    @endif

    @if ($errors->any())
      <div class="toast toast-danger">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        <div>
          <strong>{{ $errors->count() }} {{ Str::plural('error', $errors->count()) }}:</strong>
          <ul style="margin:.25rem 0 0 1rem;">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      </div>
    @endif

    <section class="card">
      <div class="inner">
        <form action="{{ $isEdit ? route('classes.update', $class->id) : route('classes.store') }}" method="POST">
          @csrf
          @if($isEdit) @method('PUT') @endif

          <div class="grid">
            <!-- Class -->
            <div class="field">
              <label class="label" for="name">Class <span class="req">*</span></label>
              <div class="control">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 6a2 2 0 0 1 2-2h6l4 4v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6z"/></svg>
                <select id="name" name="name" class="select" required>
                  <option value="">— Select Class —</option>
                  @foreach($options as $opt)
                    <option value="{{ $opt }}" {{ $selected == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                  @endforeach
                </select>
              </div>
              <p class="hint">Choose the class / grade level.</p>
            </div>

            <!-- Monthly Fee (required) -->
            <div class="field">
              <label class="label" for="fee">Monthly Fee <span class="req">*</span></label>
              <div class="group">
                <span class="addon">PKR</span>
                <input id="fee" class="input" type="number" name="fee" inputmode="decimal" step="0.01" min="0" placeholder="0.00" value="{{ old('fee', $class->fee ?? '') }}" required />
              </div>
              <p class="hint">Enter the monthly fee amount.</p>
            </div>
          </div>

          <div class="actions">
            <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Update Class' : 'Add Class' }}</button>
            @if($isEdit)
              <a href="{{ route('classes.create') }}" class="btn btn-ghost">Cancel edit</a>
            @endif
            <a class="btn btn-ghost" href="{{ route('classes.index') }}">View classes list →</a>
          </div>
        </form>
      </div>
    </section>
  </div>
</div>
@endsection