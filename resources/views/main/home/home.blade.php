{{-- resources/views/main/home/home.blade.php --}}
@extends('main.layouts.app')

@section('title', 'Home — AL-FARAN School of Excellence')
@section('body_class', 'page--home')

<style>
  :root{
    --fse-blue:#1f64c8;
    --fse-sky:#22c1f1;
    --ink:#0b1020;
    --muted:#5b6479;
    --bg:#f7fafc;
    --card:#ffffff;
    --stroke: rgba(12,18,38,.10);
    --radius:14px;
  }
  body{ background: var(--bg); color: var(--ink); }

  .wrap{ max-width: 1200px; margin: 0 auto; padding: clamp(20px, 4vw, 36px) clamp(14px, 3vw, 22px); }

  /* === HERO === */
  .hero { position: relative; width: 100%; overflow: hidden; }
  .slides { display: flex; width: 300%; transition: transform .7s ease; }
  .slide {
    min-width: 100%; height: clamp(320px, 52vw, 520px);
    position: relative; background: #000;
  }
  .slide img { width: 100%; height: 100%; object-fit: cover; object-position: center; }
  .slide::after { display: none; }
  .hero__caption {
    position:absolute; inset:0; z-index:2;
    display:flex; flex-direction:column; align-items:center; justify-content:center;
    text-align:center; color:#fff; padding: 0 16px;
  }
  .hero__title {
    font-size: clamp(26px, 4.4vw, 48px); font-weight: 900;
    background: linear-gradient(90deg, var(--fse-blue), var(--fse-sky));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    margin: 0 0 12px 0; text-shadow: 0 3px 18px rgba(0,0,0,.25);
  }
  .hero__sub { font-size: clamp(14px, 1.8vw, 20px); max-width: 880px; line-height: 1.7; margin: 0; color: #f1f5f9; }
  .dots { position: absolute; left: 0; right: 0; bottom: 14px; display: flex; justify-content: center; gap: 8px; z-index: 3; }
  .dot { width: 10px; height: 10px; border-radius: 999px; background: #e5e7eb; cursor: pointer; transition:.2s; }
  .dot.active { width: 26px; background: linear-gradient(90deg, var(--fse-blue), var(--fse-sky)); }

  /* === SECTION HEADINGS === */
  .section{ margin-top: clamp(26px, 5vw, 52px); }
  .h-title{
    font-size: clamp(22px, 2.8vw, 36px); font-weight: 900;
    text-transform: uppercase; margin: 0 0 16px 0;
    background: linear-gradient(90deg, var(--fse-blue), var(--fse-sky));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    display:inline-block;
  }
  .h-title::after{ content:""; display:block; width:70px; height:5px; margin-top:8px; border-radius:999px;
    background: linear-gradient(90deg, var(--fse-blue), var(--fse-sky)); }
  .h-sub{ font-size: clamp(15px, 1.7vw, 20px); color: var(--muted); line-height:1.9; }

  /* === CARDS === */
  .about-card{
    background: var(--card); border:1px solid var(--stroke); border-radius: var(--radius);
    padding: 32px; box-shadow: 0 10px 30px rgba(2,6,23,.08);
  }

  /* === GALLERY === */
  .gallery-sub { margin-top: 30px; }
  .gallery-sub h3 { font-size: clamp(18px, 2.4vw, 28px); font-weight: 800; margin-bottom: 14px; color: var(--fse-blue); }
  .g-grid{ display:grid; gap:14px; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); }
  .g-card{ position:relative; overflow:hidden; border-radius: 12px; }
  .g-img{ width:100%; aspect-ratio: 4 / 3; object-fit: cover; transition: transform .6s ease, filter .6s ease; }
  .g-card:hover .g-img{ transform: scale(1.08); filter: brightness(1.1); }

  /* === ACHIEVEMENTS === */
  .feat{ display:grid; gap:12px; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); }
  .chip{ background:#fff; border:1px solid var(--stroke); border-radius:12px; padding:14px; box-shadow: 0 6px 20px rgba(0,0,0,.05); }
  .chip__title{ font-weight:900; margin:0 0 4px; color:var(--ink); }
  .chip__text{ margin:0; font-size:14px; color:var(--muted); }

  /* === ACADEMICS === */
  .academics-grid{ display:grid; gap:18px; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); }
  .acad-card{ background:#fff; border-radius:14px; padding:20px; border:1px solid var(--stroke); box-shadow:0 6px 20px rgba(0,0,0,.05); }
  .acad-card h4{ margin:0 0 8px; font-size:18px; font-weight:800; color:var(--fse-blue); }
  .acad-card p{ margin:0; color:var(--muted); font-size:15px; line-height:1.6; }

  /* === TESTIMONIALS === */
  .testi-grid{ display:grid; gap:18px; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); }
  .testi{ background:#fff; border:1px solid var(--stroke); border-radius:14px; padding:20px; box-shadow:0 6px 20px rgba(0,0,0,.05); }
  .testi p{ font-size:15px; line-height:1.6; color:var(--ink); margin:0 0 12px; }
  .testi strong{ display:block; font-size:14px; color:var(--fse-blue); }

  /* === CTA BUTTONS === */
  .cta-box { text-align: center; margin: 50px 0; }
  .btn-admission {
    padding:14px 28px; font-size:18px; font-weight: 900;
    background: linear-gradient(90deg, var(--fse-blue), var(--fse-sky));
    color:#fff; border:0; border-radius: 12px;
    cursor:pointer; box-shadow: 0 8px 24px rgba(31,100,200,.25);
    transition: transform .2s, box-shadow .2s;
  }
  .btn-admission:hover { transform: translateY(-3px); box-shadow: 0 12px 28px rgba(31,100,200,.35); }
</style>

@section('content')
  {{-- HERO --}}
  <section class="hero">
    <div class="slides" id="heroSlides">
      <div class="slide">
        <img src="{{ asset('assets/images/school/hero2.jpg') }}" alt="">
        <div class="hero__caption">
          <h1 class="hero__title">Welcome to AL-FARAN</h1>
          <p class="hero__sub">Shaping tomorrow’s leaders through academics, values & creativity.</p>
        </div>
      </div>
      <div class="slide">
        <img src="{{ asset('assets/images/banner2.jpg') }}" alt="">
        <div class="hero__caption">
          <h1 class="hero__title">Safe & Caring Campus</h1>
          <p class="hero__sub">Every child thrives in a nurturing, supportive environment.</p>
        </div>
      </div>
      <div class="slide">
        <img src="{{ asset('assets/images/banner3.jpg') }}" alt="">
        <div class="hero__caption">
          <h1 class="hero__title">Holistic Development</h1>
          <p class="hero__sub">Beyond classrooms — arts, sports, leadership & more.</p>
        </div>
      </div>
    </div>
    <div class="dots" id="heroDots">
      <span class="dot active" data-i="0"></span>
      <span class="dot" data-i="1"></span>
      <span class="dot" data-i="2"></span>
    </div>
  </section>

  <main class="wrap">
    {{-- ABOUT --}}
    <section class="section about">
      <h3 class="h-title">About Our School</h3>
      <div class="about-card">
        <p class="h-sub">
          AL-FARAN School of Excellence blends strong academics with values and creativity. 
          With caring teachers and a vibrant campus, we prepare students not just for exams, but for life.
          Our mission is to nurture every child into a confident, responsible, and future-ready leader.
        </p>
      </div>
    </section>

    {{-- ACADEMICS --}}
    <section class="section academics">
      <h3 class="h-title">Academics</h3>
      <div class="academics-grid">
        <div class="acad-card">
          <h4>Strong Curriculum</h4>
          <p>Aligned with national standards, blending science, math, languages, and social sciences.</p>
        </div>
        <div class="acad-card">
          <h4>Activity-Based Learning</h4>
          <p>Hands-on projects, group activities, and practical exposure to make learning fun.</p>
        </div>
        <div class="acad-card">
          <h4>Modern Classrooms</h4>
          <p>Smart boards, multimedia teaching aids, and a focus on digital literacy.</p>
        </div>
      </div>
    </section>

    {{-- CAMPUS LIFE --}}
    <section class="section gallery">
      <h3 class="h-title">Campus Life</h3>
      <div class="gallery-sub">
        <h3>Award Ceremony</h3>
        <div class="g-grid">
          <article class="g-card"><img class="g-img" src="{{ asset('assets/images/school/award1.jpg') }}"></article>
          <article class="g-card"><img class="g-img" src="{{ asset('assets/images/school/award2.jpg') }}"></article>
          <article class="g-card"><img class="g-img" src="{{ asset('assets/images/school/award3.jpg') }}"></article>
          <article class="g-card"><img class="g-img" src="{{ asset('assets/images/school/award4.jpg') }}"></article>
        </div>
      </div>
      <div class="gallery-sub">
        <h3>School Function</h3>
        <div class="g-grid">
          <article class="g-card"><img class="g-img" src="{{ asset('assets/images/school/function1.jpg') }}"></article>
          <article class="g-card"><img class="g-img" src="{{ asset('assets/images/school/function2.jpg') }}"></article>
          <article class="g-card"><img class="g-img" src="{{ asset('assets/images/school/function3.jpg') }}"></article>
          <article class="g-card"><img class="g-img" src="{{ asset('assets/images/school/function4.jpg') }}"></article>
        </div>
      </div>
      <div class="gallery-sub">
        <h3>Class Room</h3>
        <div class="g-grid">
          <article class="g-card"><img class="g-img" src="{{ asset('assets/images/school/class1.jpg') }}"></article>
          <article class="g-card"><img class="g-img" src="{{ asset('assets/images/school/class2.jpg') }}"></article>
          <article class="g-card"><img class="g-img" src="{{ asset('assets/images/school/class3.jpg') }}"></article>
          <article class="g-card"><img class="g-img" src="{{ asset('assets/images/school/class4.jpg') }}"></article>
        </div>
      </div>
    </section>

    {{-- ADMISSION CTA --}}
    <div class="cta-box">
      <a href="{{ route('admission') }}"><button class="btn-admission" > Get Admission</button></a>
    </div>

    {{-- ACHIEVEMENTS --}}
    <section class="section achievements">
      <h3 class="h-title">Our Achievements</h3>
      <div class="feat">
        <div class="chip"><p class="chip__title">Board Toppers</p><p class="chip__text">Strong performance in board exams with top results.</p></div>
        <div class="chip"><p class="chip__title">Debate Champions</p><p class="chip__text">Winners of city-level debates and declamation contests.</p></div>
        <div class="chip"><p class="chip__title">Science & Sports</p><p class="chip__text">Prizes in science fairs, robotics, athletics and more.</p></div>
      </div>
    </section>

    {{-- TESTIMONIALS --}}
    <section class="section testimonials">
      <h3 class="h-title">What Parents Say</h3>
      <div class="testi-grid">
        <div class="testi">
          <p>“My son has become more confident and active since joining AL-FARAN. The teachers give attention like family.”</p>
          <strong>— Ahmed Raza, Depalpur</strong>
        </div>
        <div class="testi">
          <p>“I shifted my daughter from another school and I’m very happy. The focus on both academics and values is amazing.”</p>
          <strong>— Maria Khan, Depalpur</strong>
        </div>
        <div class="testi">
          <p>“The best part is how they involve parents and keep us updated. A real caring environment for children.”</p>
          <strong>— Faisal Mehmood, Depalpur</strong>
        </div>
      </div>
    </section>

    {{-- CONTACT --}}
   
  </main>
@endsection

<script>
(function(){
  const slides = document.getElementById('heroSlides');
  const dots = document.querySelectorAll('#heroDots .dot');
  let i=0;
  function go(n){
    i = (n + dots.length) % dots.length;
    slides.style.transform = `translateX(-${i*100}%)`;
    dots.forEach(d=>d.classList.remove('active'));
    dots[i].classList.add('active');
  }
  dots.forEach(d=>d.addEventListener('click', ()=>go(+d.dataset.i)));
  setInterval(()=>go(i+1), 5000);
})();
</script>
