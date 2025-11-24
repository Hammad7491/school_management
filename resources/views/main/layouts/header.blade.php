{{-- Welcome Page Header --}}
<header class="site-header" x-data>
  <div class="site-header__wrap">

    {{-- Brand --}}
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
        <li><a href="{{ route('fee') }}" class="nav__link {{ request()->is('fee') ? 'is-active' : '' }}">Fee</a></li>
        <li><a href="{{ route('faculty') }}" class="nav__link {{ request()->is('faculty') ? 'is-active' : '' }}">Faculty</a></li>
        <li><a href="{{ route('vision') }}" class="nav__link {{ request()->is('vision') ? 'is-active' : '' }}">Our Vision</a></li>
        <li><a href="{{ route('computer.courses') }}" class="nav__link {{ request()->is('courses') ? 'is-active' : '' }}">Courses</a></li>
        <li><a href="{{ route('admission') }}" class="nav__link {{ request()->is('admission') ? 'is-active' : '' }}">Admission</a></li>
      </ul>
    </nav>

    {{-- CTA --}}
    <div class="cta">
      @auth
        <a href="{{ route('admin.dashboard') }}" class="btn btn--primary">Dashboard</a>
      @else
        <a href="{{ route('login') }}" class="btn btn--primary">Login</a>
      @endauth
    </div>

    {{-- Mobile Toggle --}}
    <button class="hamburger" id="navToggle" aria-controls="siteNav" aria-expanded="false">
      <span class="line line1"></span>
      <span class="line line2"></span>
      <span class="line line3"></span>
    </button>
  </div>

  {{-- Mobile Panel --}}
  <div class="mobile-panel" id="mobilePanel" aria-hidden="true">
    <nav class="mobile-nav">
      <a href="{{ url('/') }}" class="mobile-nav__link {{ request()->is('/') ? 'is-active' : '' }}">Home</a>
      <a href="{{ route('fee') }}" class="mobile-nav__link {{ request()->is('fee') ? 'is-active' : '' }}">Fee</a>
      <a href="{{ route('faculty') }}" class="mobile-nav__link {{ request()->is('faculty') ? 'is-active' : '' }}">Faculty</a>
      <a href="{{ route('vision') }}" class="mobile-nav__link {{ request()->is('vision') ? 'is-active' : '' }}">Our Vision</a>
      <a href="{{ route('computer.courses') }}" class="mobile-nav__link {{ request()->is('courses') ? 'is-active' : '' }}">Courses</a>
      <a href="{{ route('admission') }}" class="mobile-nav__link {{ request()->is('admission') ? 'is-active' : '' }}">Admission</a>

      @auth
        <a href="{{ route('admin.dashboard') }}" class="mobile-nav__btn">Dashboard</a>
      @else
        <a href="{{ route('login') }}" class="mobile-nav__btn">Login</a>
      @endauth
    </nav>
  </div>

  <div class="backdrop" id="backdrop" hidden></div>
</header>



<style>
  body { margin: 0; }

  :root{
    --fse-blue:#1f64c8;
    --fse-sky:#22c1f1;
    --fse-gold:#e7b308;
    --bg:#fff;
    --ink:#0b1020;
    --muted:#5b6479;
    --card:#ffffff;
    --stroke:rgba(12,18,38,.09);
  }

  /* HEADER */
  .site-header{
    position:sticky; top:0; z-index:50; width:100%;
    background:linear-gradient(0deg, rgba(255,255,255,.92), rgba(255,255,255,.85));
    backdrop-filter:blur(10px);
    border-bottom:2px solid rgba(31,100,200,.18);
  }
  .site-header__wrap{
    width:100%; padding:10px clamp(14px,3vw,28px);
    display:flex; align-items:center; gap:14px;
  }

  /* LOGO */
  .brand{ display:flex; align-items:center; gap:12px; text-decoration:none; }
  .brand__logo{
    height:clamp(56px,6vw,72px);
    aspect-ratio:1/1; object-fit:cover;
    border-radius:14px; box-shadow:0 4px 14px rgba(0,0,0,.15);
  }
  .brand__text{ line-height:1; }
  .brand__name{ font-weight:900; color:var(--ink); font-size:clamp(18px,2.2vw,22px); }
  .brand__tag{ font-size:12px; color:var(--muted); }
  .brand__tag::after{ content:" • FSE"; color:var(--fse-gold); font-weight:700; }

  /* NAV */
  .nav{ margin-left:auto; }
  .nav__list{ display:flex; gap:12px; list-style:none; padding:0; margin:0; }
  .nav__link{
    text-decoration:none; color:var(--ink); font-weight:800;
    padding:10px 14px; border-radius:12px;
    transition:.25s ease;
  }
  .nav__link:hover, .nav__link.is-active{ color:var(--fse-blue); }

  /* BUTTON */
  .cta{ margin-left:8px; }
  .btn--primary{
    background:linear-gradient(90deg,var(--fse-blue),var(--fse-sky));
    padding:10px 16px; border-radius:12px; color:#fff; font-weight:900;
  }

  /* HAMBURGER NEW FIXED */
  .hamburger{
    margin-left:auto;
    height:48px; width:48px;
    border-radius:12px;
    border:1px solid var(--stroke);
    background:var(--card);
    display:none; align-items:center; justify-content:center;
    position:relative; cursor:pointer;
  }
  .hamburger .line{
    width:24px; height:3px;
    background:var(--ink);
    border-radius:3px;
    position:absolute;
    transition:.25s ease;
  }
  .line1{ top:14px; }
  .line2{ top:22px; }
  .line3{ top:30px; }

  .hamburger[aria-expanded="true"] .line1{ transform:translateY(8px) rotate(45deg); }
  .hamburger[aria-expanded="true"] .line2{ opacity:0; }
  .hamburger[aria-expanded="true"] .line3{ transform:translateY(-8px) rotate(-45deg); }

  /* MOBILE NAV */
  .mobile-panel{
    position:fixed; inset:64px 0 auto 0;
    background:var(--card); border-top:3px solid var(--fse-blue);
    opacity:0; pointer-events:none; transform:translateY(-8px);
    transition:.18s ease;
  }
  .mobile-panel.open{
    opacity:1; pointer-events:auto; transform:translateY(0);
  }

  .mobile-nav{ padding:14px 20px; display:grid; gap:12px; }
  .mobile-nav__link{
    padding:12px; border-radius:12px; font-weight:800; color:var(--ink); text-decoration:none;
  }
  .mobile-nav__link.is-active{ color:var(--fse-blue); }
  .mobile-nav__btn{
    background:linear-gradient(90deg,var(--fse-blue),var(--fse-sky));
    padding:12px; border-radius:12px; color:#fff; text-align:center;
  }

  @media(max-width:1024px){
    .nav, .cta{ display:none; }
    .hamburger{ display:flex; }
  }
  @media(max-width:480px){
    .brand__text{ display:none; }
  }
</style>



<script>
  (function () {
    const toggle   = document.getElementById('navToggle');
    const panel    = document.getElementById('mobilePanel');
    const backdrop = document.getElementById('backdrop');

    function openMenu(){
      toggle.setAttribute('aria-expanded','true');
      panel.classList.add('open');
      backdrop.hidden=false;
    }

    function closeMenu(){
      toggle.setAttribute('aria-expanded','false');
      panel.classList.remove('open');
      backdrop.hidden=true;
    }

    toggle.addEventListener('click', () =>
      toggle.getAttribute('aria-expanded') === 'true' ? closeMenu() : openMenu()
    );

    backdrop.addEventListener('click', closeMenu);

    panel.addEventListener('click', e => {
      if (e.target.matches('a')) closeMenu();
    });

    window.addEventListener('resize', () => {
      if (window.innerWidth > 1024) closeMenu();
    });
  })();
</script>
  