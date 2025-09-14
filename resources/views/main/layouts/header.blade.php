{{-- Welcome Page Header (fluid, no free space on the left) --}}
<header class="site-header" x-data>
  <div class="site-header__wrap">
    {{-- Brand / Logo --}}
    <a href="{{ url('/') }}" class="brand">
      <img src="{{ asset('assets/images/logo.png') }}" alt="School logo" class="brand__logo">
      <div class="brand__text">
        <span class="brand__name">Your School</span>
        <span class="brand__tag">learn • grow • lead</span>
      </div>
    </a>

    {{-- Desktop Nav --}}
    <nav class="nav" id="siteNav" aria-label="Primary">
      <ul class="nav__list">
        <li><a href="{{ url('/') }}" class="nav__link {{ request()->is('/') ? 'is-active' : '' }}">Home</a></li>
        <li><a href="#fees" class="nav__link">Fee<br class="d-lg-none"> Management</a></li>
        <li><a href="#faculty" class="nav__link">Faculty</a></li>
        <li><a href="#about" class="nav__link">About</a></li>
        <li><a href="#vision" class="nav__link">Our<br class="d-lg-none"> Vision</a></li>
        <li><a href="#courses" class="nav__link">Computer<br class="d-lg-none"> Courses</a></li>
      </ul>
    </nav>

    {{-- Right CTA --}}
    <div class="cta">
      @auth
        <a href="{{ route('admin.dashboard') }}" class="btn btn--primary">Dashboard</a>
      @else
        <a href="{{ route('login') }}" class="btn btn--primary">Login</a>
      @endauth
    </div>

    {{-- Mobile Toggle --}}
    <button class="hamburger" id="navToggle" aria-controls="siteNav" aria-expanded="false" aria-label="Toggle navigation">
      <span></span><span></span><span></span>
    </button>
  </div>

  {{-- Mobile Off-canvas --}}
  <div class="mobile-panel" id="mobilePanel" aria-hidden="true">
    <nav class="mobile-nav" aria-label="Mobile primary">
      <a href="{{ url('/') }}" class="mobile-nav__link">Home</a>
      <a href="#fees" class="mobile-nav__link">Fee Management</a>
      <a href="#faculty" class="mobile-nav__link">Faculty</a>
      <a href="#about" class="mobile-nav__link">About</a>
      <a href="#vision" class="mobile-nav__link">Our Vision</a>
      <a href="#courses" class="mobile-nav__link">Computer Courses</a>
      @auth
        <a href="{{ route('admin.dashboard') }}" class="mobile-nav__btn">Dashboard</a>
      @else
        <a href="{{ route('login') }}" class="mobile-nav__btn">Login</a>
      @endauth
    </nav>
  </div>

  {{-- Backdrop --}}
  <div class="backdrop" id="backdrop" hidden></div>
</header>

<style>
  /* kill any default browser margin that could create side gaps */
  body { margin: 0; }

  :root{
    --bg:#f7f9ff; --ink:#0b1020; --muted:#64748b; --card:#ffffff; --stroke:rgba(15,23,42,.1);
    --brand1:#6a7bff; --brand2:#22d3ee; --ring:rgba(106,123,255,.35); --radius:16px;
  }
  @media (prefers-color-scheme: dark){
    :root{ --bg:#0b1020; --ink:#e6e9f5; --muted:#aab3c5; --card:#0f1830; --stroke:rgba(255,255,255,.12); --ring:rgba(106,123,255,.55); }
  }

  .site-header{
    position: sticky; top:0; z-index:50;
    width:100%;
    background:
      radial-gradient(800px 400px at -10% -20%, rgba(106,123,255,.12), transparent 60%),
      radial-gradient(700px 500px at 110% -30%, rgba(34,211,238,.12), transparent 60%),
      rgba(255,255,255,.7);
    backdrop-filter: blur(10px);
    border-bottom:1px solid var(--stroke);
  }

  /* FLUID wrapper: fills the full width, with responsive side padding */
  .site-header__wrap{
    width:100%;
    padding: 10px clamp(14px, 3vw, 28px);
    display:flex; align-items:center; gap:14px;
    box-sizing: border-box;
  }

  .brand{ display:flex; align-items:center; gap:10px; text-decoration:none; }
  .brand__logo{ height:42px; width:auto; border-radius:10px; display:block; }
  .brand__text{ line-height:1; }
  .brand__name{ font-weight:900; color:var(--ink); font-size: clamp(18px, 2.2vw, 22px); letter-spacing:.2px; }
  .brand__tag{ display:block; font-size:12px; color:var(--muted); margin-top:2px; }

  .nav{ margin-left:auto; }
  .nav__list{ display:flex; align-items:center; gap:12px; list-style:none; padding:0; margin:0; flex-wrap:wrap; }
  .nav__link{
    text-decoration:none; color:var(--ink); font-weight:800; padding:10px 12px; border-radius:12px;
    border:1px solid transparent; transition:.2s ease; white-space:nowrap;
  }
  .nav__link:hover{ border-color:var(--stroke); box-shadow:0 0 0 4px var(--ring); }
  .nav__link.is-active{
    background: linear-gradient(90deg,var(--brand1),var(--brand2));
    -webkit-background-clip:text; background-clip:text; color:transparent;
  }

  .cta{ margin-left:8px; }
  .btn{
    display:inline-flex; align-items:center; justify-content:center; gap:.5rem;
    padding:10px 14px; border-radius:12px; font-weight:900; text-decoration:none;
    border:1px solid var(--stroke); color:var(--ink); background:var(--card);
  }
  .btn:hover{ box-shadow:0 0 0 4px var(--ring); }
  .btn--primary{
    background: linear-gradient(90deg,var(--brand1),var(--brand2)); border-color:transparent; color:#fff;
    box-shadow:0 12px 28px rgba(106,123,255,.25);
  }
  .btn--primary:hover{ box-shadow:0 0 0 4px var(--ring); }

  /* Hamburger (mobile) */
  .hamburger{
    margin-left:8px; border:1px solid var(--stroke); background:var(--card); color:var(--ink);
    height:40px; width:44px; border-radius:12px; display:none; align-items:center; justify-content:center;
  }
  .hamburger span{ height:2px; width:20px; background:var(--ink); display:block; position:relative; }
  .hamburger span+span{ margin-top:4px; }
  .hamburger[aria-expanded="true"] span:nth-child(1){ transform: translateY(6px) rotate(45deg); }
  .hamburger[aria-expanded="true"] span:nth-child(2){ opacity:0; }
  .hamburger[aria-expanded="true"] span:nth-child(3){ transform: translateY(-6px) rotate(-45deg); }

  /* Mobile panel */
  .mobile-panel{
    position: fixed; inset: 64px 0 auto 0;
    background:var(--card); border-top:1px solid var(--stroke);
    transform: translateY(-8px); opacity:0; pointer-events:none; transition:.18s ease;
  }
  .mobile-panel.open{ transform:translateY(0); opacity:1; pointer-events:auto; }
  .mobile-nav{ padding:12px clamp(14px, 3vw, 28px) 18px; display:grid; gap:6px; }
  .mobile-nav__link{
    display:block; padding:12px 10px; border-radius:12px; color:var(--ink); text-decoration:none; font-weight:800;
    border:1px solid transparent;
  }
  .mobile-nav__link:hover{ border-color:var(--stroke); background:rgba(106,123,255,.06); }
  .mobile-nav__btn{
    margin-top:6px; display:inline-flex; align-items:center; justify-content:center; padding:12px 14px;
    border-radius:12px; color:#fff; text-decoration:none; background:linear-gradient(90deg,var(--brand1),var(--brand2));
  }

  .backdrop{ position:fixed; inset:0; background:rgba(2,6,23,.35); backdrop-filter: blur(2px); }

  /* Responsive */
  @media (max-width: 1180px){
    .nav__list{ gap:6px; }
  }
  @media (max-width: 1024px){
    .nav, .cta{ display:none; }
    .hamburger{ display:flex; margin-left:auto; }
  }

  /* helper to hide <br> on large screens */
  .d-lg-none{ display:none; }
  @media (max-width: 1024px){ .d-lg-none{ display:inline; } }
</style>

<script>
  (function () {
    const toggle   = document.getElementById('navToggle');
    const panel    = document.getElementById('mobilePanel');
    const backdrop = document.getElementById('backdrop');

    function openMenu(){ toggle.setAttribute('aria-expanded','true'); panel.classList.add('open'); backdrop.hidden=false; }
    function closeMenu(){ toggle.setAttribute('aria-expanded','false'); panel.classList.remove('open'); backdrop.hidden=true; }

    toggle.addEventListener('click', () => toggle.getAttribute('aria-expanded') === 'true' ? closeMenu() : openMenu());
    backdrop.addEventListener('click', closeMenu);
    panel.addEventListener('click', e => { if (e.target.matches('a')) closeMenu(); });
    window.addEventListener('resize', () => { if (window.innerWidth > 1024) closeMenu(); });
  })();
</script>
