{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')

@section('content')
@php
  use Illuminate\Support\Str;

  $user  = auth()->user();
  $stats = $stats ?? [];

  $displayName = $user?->name ? Str::title($user->name) : 'Admin';
  $initials    = $user?->name
      ? collect(explode(' ', trim($user->name)))->filter()->map(fn($p)=>Str::upper(Str::substr($p,0,1)))->take(2)->implode('')
      : 'A';

  // If later you add a profile image column for admin user, replace this:
  $adminPhotoUrl = null; // e.g. $user->profile_image_path ? asset('storage/'.$user->profile_image_path) : null;
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
  .wrap{ max-width:1200px; margin:0 auto; padding:24px 12px 72px; }

  .hero{
    background:var(--card);
    border:1px solid var(--stroke);
    border-radius:22px;
    padding:28px 24px;
    box-shadow:0 24px 60px rgba(2,6,23,.10);
    margin-bottom:18px;
    position:relative;
    overflow:hidden;
  }

  .hero-top{
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    gap:12px;
  }

  .hero h1{ font-size: clamp(28px,5vw,56px); font-weight:900; line-height:1.04; margin:0; }
  .hero h1 span{ background:linear-gradient(90deg,var(--brand1),var(--brand2)); -webkit-background-clip:text; background-clip:text; color:transparent; }
  .subtle{ color:var(--muted); font-weight:600; margin-top:6px; }

  /* Admin avatar */
  .avatar{
    width:46px;
    height:46px;
    border-radius:999px;
    border:1px solid var(--stroke);
    background:linear-gradient(135deg, rgba(106,123,255,.22), rgba(34,211,238,.20));
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:900;
    box-shadow:0 12px 28px rgba(2,6,23,.10);
    flex:0 0 auto;
    overflow:hidden;
  }
  .avatar img{
    width:100%;
    height:100%;
    object-fit:cover;
    display:block;
  }

  .grid{ display:grid; gap:16px; }
  .stats{ grid-template-columns: repeat(3, minmax(0,1fr)); }
  @media (max-width: 900px){ .stats{ grid-template-columns: 1fr; } }

  .stat{
    background:var(--card); border:1px solid var(--stroke); border-radius:18px; padding:18px;
    display:flex; align-items:center; justify-content:space-between; gap:12px;
    box-shadow:0 12px 32px rgba(2,6,23,.08);
  }
  .stat .label{ color:var(--muted); font-weight:800; }
  .stat .value{ font-weight:900; font-size:28px; }
  .ico{
    height:44px;width:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;
    background:linear-gradient(135deg, rgba(106,123,255,.18), rgba(34,211,238,.18));
  }

  .two{ display:grid; grid-template-columns: 1fr 0.9fr; gap:16px; margin-top:12px; }
  @media (max-width: 900px){ .two{ grid-template-columns: 1fr; } }

  .cardx{ background:var(--card); border:1px solid var(--stroke); border-radius:18px; box-shadow:0 18px 45px rgba(2,6,23,.08); }
  .cardx h3{ margin:0; padding:14px 16px; border-bottom:1px solid var(--stroke); font-weight:900; }

  .list{ margin:0; padding:0; list-style:none; }
  .row{ display:flex; align-items:center; justify-content:space-between; gap:12px; padding:16px; border-top:1px solid var(--stroke); }
  .row:first-child{ border-top:0; }
  .row a.row-link{ color:inherit; text-decoration:none; flex:1 1 auto; display:flex; align-items:center; gap:10px; }
  .row a.row-link:hover{ text-decoration:underline; }

  .count-pill, .count-pill--disabled{
    min-width:32px; height:32px; border-radius:999px; display:flex; align-items:center; justify-content:center;
    font-weight:900; border:1px solid rgba(245,158,11,.35); background:rgba(245,158,11,.14); color:#7c2d12; padding:0 10px;
  }
  .count-pill{ text-decoration:none; }
  .count-pill--disabled{ opacity:.6; cursor:default; }

  .actions{ padding:14px 16px; display:flex; gap:12px; flex-wrap:wrap; border-top:1px solid var(--stroke); }
  .btnx{
    display:inline-flex; align-items:center; gap:.5rem; padding:10px 14px; border-radius:12px; border:1px solid var(--stroke);
    color:var(--ink); background:var(--card); text-decoration:none; font-weight:900;
  }
  .btnx:hover{ box-shadow:0 0 0 4px var(--ring); }

  .quick{ padding:8px 16px 16px; }
  .quick .chips{ display:grid; grid-template-columns: 1fr 1fr; gap:12px; }
  @media (max-width: 520px){ .quick .chips{ grid-template-columns: 1fr; } }
  .chip{
    display:flex; align-items:center; gap:.6rem; padding:12px 14px; border-radius:12px; border:1px solid var(--stroke);
    font-weight:900; color:var(--ink); background:var(--card); text-decoration:none;
  }
  .chip:hover{ box-shadow:0 0 0 4px var(--ring); }
</style>

<div class="page">
  <div class="wrap">

    {{-- Welcome --}}
    <div class="hero">
      <div class="hero-top">
        <div>
          <h1>Welcome, <span>{{ $displayName }}</span></h1>
          <div class="subtle">Here’s a quick snapshot of your activity.</div>
        </div>

        <div class="avatar" title="{{ $displayName }}">
          @if($adminPhotoUrl)
            <img src="{{ $adminPhotoUrl }}" alt="Admin Photo">
          @else
            {{ $initials }}
          @endif
        </div>
      </div>
    </div>

    {{-- Top stats --}}
    <div class="grid stats">
      <div class="stat">
        <div>
          <div class="label">Students added by you</div>
          <div class="value">{{ $stats['students_by_me'] ?? '—' }}</div>
        </div>
        <div class="ico"><iconify-icon icon="mdi:account-plus-outline" style="font-size:22px;"></iconify-icon></div>
      </div>

      <div class="stat">
        <div>
          <div class="label">Total Classes</div>
          <div class="value">{{ $stats['classes_total'] ?? 0 }}</div>
        </div>
        <div class="ico"><iconify-icon icon="mdi:school-outline" style="font-size:22px;"></iconify-icon></div>
      </div>

      <div class="stat">
        <div>
          <div class="label">Total Courses</div>
          <div class="value">{{ $stats['courses_total'] ?? 0 }}</div>
        </div>
        <div class="ico"><iconify-icon icon="mdi:book-open-variant-outline" style="font-size:22px;"></iconify-icon></div>
      </div>
    </div>

    <div class="two">
      {{-- Attention needed --}}
      <div class="cardx">
        <h3>Attention Needed</h3>
        <ul class="list">

          {{-- Vacation requests waiting review --}}
          <li class="row">
            <a class="row-link" href="{{ route('admin.vacations.index', ['status' => 'pending']) }}">
              Vacation requests waiting review
            </a>

            @php $vr = (int)($stats['vacations_pending'] ?? 0); @endphp
            @if($vr > 0)
              <a class="count-pill"
                 href="{{ route('admin.vacations.index', ['status' => 'pending']) }}"
                 title="View pending vacation requests">{{ $vr }}</a>
            @else
              <span class="count-pill--disabled">0</span>
            @endif
          </li>

          {{-- Draft notifications --}}
          <li class="row">
            <span class="row-link" style="cursor:default">Draft notifications</span>
            @php $dn = (int)($stats['notifications_draft'] ?? 0); @endphp
            @if($dn > 0)
              <a class="count-pill" href="{{ route('admin.notifications.index') }}?status=draft"
                 title="Go to notifications drafts">{{ $dn }}</a>
            @else
              <span class="count-pill--disabled">0</span>
            @endif
          </li>
        </ul>

        <div class="actions">
          <a href="{{ route('admin.vacations.index', ['status' => 'pending']) }}" class="btnx">
            <iconify-icon icon="mdi:clipboard-list-outline"></iconify-icon> Review Vacations
          </a>
          <a href="{{ route('admin.notifications.index') }}" class="btnx">
            <iconify-icon icon="mdi:bullhorn-outline"></iconify-icon> Manage Notifications
          </a>
        </div>
      </div>

      {{-- Quick Links --}}
      <div class="cardx">
        <h3>Quick Links</h3>
        <div class="quick">
          <div class="chips">
            <a class="chip" href="{{ route('students.index') }}">
              <iconify-icon icon="mdi:account-multiple-outline"></iconify-icon> Students
            </a>
            <a class="chip" href="{{ route('students.create') }}">
              <iconify-icon icon="mdi:account-plus-outline"></iconify-icon> Add Student
            </a>
            <a class="chip" href="{{ route('classes.index') }}">
              <iconify-icon icon="mdi:school-outline"></iconify-icon> Classes
            </a>
            <a class="chip" href="{{ route('courses.index') }}">
              <iconify-icon icon="mdi:book-open-variant-outline"></iconify-icon> Courses
            </a>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
