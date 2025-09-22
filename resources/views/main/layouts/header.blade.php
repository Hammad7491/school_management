{{-- Welcome Page Header --}}
<header class="site-header" x-data>
  <div class="site-header__wrap">
    {{-- Brand / Logo --}}
    <a href="{{ url('/') }}" class="brand">
      <img src="{{ asset('assets/images/school/logo.jpg') }}" alt="Al-Faran School of Excellence logo" class="brand__logo">
      <div class="brand__text">
        <span class="brand__name">Al-Faran School of Excellence</span>
        <span class="brand__tag">learn • grow • lead</span>
      </div>
    </a>

    {{-- Desktop Nav --}}
    <nav class="nav" id="siteNav" aria-label="Primary">
      <ul class="nav__list">
        <li><a href="{{ url('/') }}" class="nav__link {{ request()->is('/') ? 'is-active' : '' }}">Home</a></li>
        <a href="{{ route('fee') }}" class="mobile-nav__link">Fee</a>
        <li><a href="#faculty" class="nav__link">Faculty</a></li>
        <li><a href="#about" class="nav__link">About</a></li>
        <li><a href="#vision" class="nav__link">Our Vision</a></li>
        <li><a href="#courses" class="nav__link">Courses</a></li>
        <li><a href="{{ route('admission') }}" class="nav__link">Admission</a></li>
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
      <a href="{{ route('fee') }}" class="mobile-nav__link">Fee</a>
      <a href="#faculty" class="mobile-nav__link">Faculty</a>
      <a href="#about" class="mobile-nav__link">About</a>
      <a href="#vision" class="mobile-nav__link">Our Vision</a>
      <a href="#courses" class="mobile-nav__link">Courses</a>
      <a href="#admission" class="mobile-nav__link">Admission</a>
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
  body { margin: 0; }

  :root{
    --fse-blue: #1f64c8;
    --fse-sky:  #22c1f1;
    --fse-gold: #e7b308;
    --bg:#fff;
    --ink:#0b1020;
    --muted:#5b6479;
    --card:#ffffff;
    --stroke:rgba(12,18,38,.09);
    --brand1: var(--fse-blue);
    --brand2: var(--fse-sky);
    --ring:  rgba(31,100,200,.28);
    --radius:16px;
  }

  /* HEADER */
  .site-header{
    position: sticky; top:0; z-index:50; width:100%;
    background:linear-gradient(0deg, rgba(255,255,255,.92), rgba(255,255,255,.85));
    backdrop-filter: blur(10px);
    border-bottom: 2px solid rgba(31,100,200,.18);
  }
  .site-header__wrap{
    width:100%; padding: 10px clamp(14px, 3vw, 28px);
    display:flex; align-items:center; gap:14px; box-sizing:border-box;
  }

  /* LOGO */
  .brand{ display:flex; align-items:center; gap:12px; text-decoration:none; }
  .brand__logo{
    height: clamp(56px, 6vw, 72px);
    aspect-ratio: 1 / 1;
    object-fit: cover;
    border-radius:14px;
    box-shadow:0 4px 14px rgba(0,0,0,.15);
  }
  .brand__text{ line-height:1; }
  .brand__name{ font-weight:900; color:var(--ink); font-size: clamp(18px, 2.2vw, 22px); }
  .brand__tag{ display:block; font-size:12px; color:var(--muted); margin-top:2px; }
  .brand__tag::after{ content:" • FSE"; color: var(--fse-gold); font-weight:700; }

  /* NAV */
  .nav{ margin-left:auto; }
  .nav__list{ display:flex; gap:12px; list-style:none; padding:0; margin:0; flex-wrap:wrap; }
  .nav__link{
    text-decoration:none; color:var(--ink); font-weight:800;
    padding:10px 12px; border-radius:12px; border:1px solid transparent; transition:.2s ease;
  }
  .nav__link:hover{
    border-color: rgba(31,100,200,.25);
    background: rgba(34,193,241,.06);
  }
  .nav__link.is-active{
    background: linear-gradient(90deg, var(--brand1), var(--brand2));
    -webkit-background-clip:text; background-clip:text; color:transparent;
  }

  /* BUTTONS */
  .cta{ margin-left:8px; display:flex; gap:10px; }
  .btn{
    padding:10px 16px; border-radius:12px; font-weight:900; text-decoration:none;
    display:inline-flex; align-items:center; justify-content:center;
  }
  .btn--primary{
    background: linear-gradient(90deg, var(--fse-blue), var(--fse-sky));
    color:#fff; box-shadow:0 6px 20px rgba(31,100,200,.25);
  }

  /* HAMBURGER */
  .hamburger{ margin-left:8px; border:1px solid var(--stroke); background:var(--card); color:var(--ink);
    height:44px; width:48px; border-radius:12px; display:none; align-items:center; justify-content:center; }
  .hamburger span{ height:2px; width:22px; background:var(--ink); display:block; position:relative; }
  .hamburger span+span{ margin-top:4px; }
  .hamburger[aria-expanded="true"] span:nth-child(1){ transform: translateY(6px) rotate(45deg); }
  .hamburger[aria-expanded="true"] span:nth-child(2){ opacity:0; }
  .hamburger[aria-expanded="true"] span:nth-child(3){ transform: translateY(-6px) rotate(-45deg); }

  /* MOBILE NAV */
  .mobile-panel{ position: fixed; inset: 64px 0 auto 0; background:var(--card);
    border-top:3px solid var(--fse-blue); transform: translateY(-8px);
    opacity:0; pointer-events:none; transition:.18s ease; }
  .mobile-panel.open{ transform:translateY(0); opacity:1; pointer-events:auto; }
  .mobile-nav{ padding:14px 20px; display:grid; gap:8px; }
  .mobile-nav__link{ padding:12px; border-radius:12px; font-weight:800; color:var(--ink); text-decoration:none; }
  .mobile-nav__btn{ padding:12px; border-radius:12px; color:#fff; text-align:center; background:linear-gradient(90deg,var(--fse-blue),var(--fse-sky)); }

  /* RESPONSIVE */
  @media (max-width: 1024px){
    .nav, .cta{ display:none; }
    .hamburger{ display:flex; margin-left:auto; }
  }
  @media (max-width: 480px){
    .brand__text{ display:none; }
  }
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
