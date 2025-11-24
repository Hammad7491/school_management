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
  body{ background: var(--bg); color: var(--ink); margin:0; padding:0; }

  .wrap{ max-width: 1200px; margin: 0 auto; padding: clamp(20px, 4vw, 36px) clamp(14px, 3vw, 22px); }

  /* === NEW HERO (NO SLIDER, NO IMAGE) === */
  .hero {
    background: linear-gradient(135deg, var(--fse-blue), var(--fse-sky));
    padding: 110px 20px;
    text-align: center;
    color: #fff;
    position: relative;
  }
  .hero h1 {
    font-size: clamp(32px, 5vw, 58px);
    font-weight: 900;
    margin: 0 0 14px;
  }
  .hero p {
    font-size: clamp(16px, 2vw, 20px);
    max-width: 760px;
    margin: 0 auto;
    opacity: .95;
  }

  /* === SECTION HEADINGS === */
  .section{ margin-top: clamp(26px, 5vw, 52px); }
  .h-title{
    font-size: clamp(22px, 2.8vw, 36px); font-weight: 900;
    text-transform: uppercase; margin: 0 0 16px 0;
    background: linear-gradient(90deg, var(--fse-blue), var(--fse-sky));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    display:inline-block;
  }
  .h-title::after{
    content:""; display:block; width:70px; height:5px; margin-top:8px; border-radius:999px;
    background: linear-gradient(90deg, var(--fse-blue), var(--fse-sky));
  }
  .h-sub{ font-size: clamp(15px, 1.7vw, 20px); color: var(--muted); line-height:1.9; }

  /* CARDS */
  .about-card{
    background: var(--card); border:1px solid var(--stroke); border-radius: var(--radius);
    padding: 32px; box-shadow: 0 10px 30px rgba(2,6,23,.08);
  }

  /* GALLERY */
  .gallery-sub { margin-top: 30px; }
  .gallery-sub h3 { font-size: clamp(18px, 2.4vw, 28px); font-weight: 800; margin-bottom: 14px; color: var(--fse-blue); }
  .g-grid{ display:grid; gap:14px; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); }
  .g-card{ position:relative; overflow:hidden; border-radius: 12px; }
  .g-img{ width:100%; aspect-ratio: 4 / 3; object-fit: cover; transition: transform .6s ease, filter .6s ease; }
  .g-card:hover .g-img{ transform: scale(1.08); filter: brightness(1.1); }

  /* ACHIEVEMENTS */
  .feat{ display:grid; gap:12px; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); }
  .chip{ background:#fff; border:1px solid var(--stroke); border-radius:12px; padding:14px; box-shadow: 0 6px 20px rgba(0,0,0,.05); }

  /* ACADEMICS */
  .academics-grid{ display:grid; gap:18px; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); }
  .acad-card{ background:#fff; border-radius:14px; padding:20px; border:1px solid var(--stroke); box-shadow:0 6px 20px rgba(0,0,0,.05); }
  .acad-card h4{ margin:0 0 8px; font-size:18px; font-weight:800; color:var(--fse-blue); }

  /* TESTIMONIALS */
  .testi-grid{ display:grid; gap:18px; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); }
  .testi{ background:#fff; border:1px solid var(--stroke); border-radius:14px; padding:20px; box-shadow:0 6px 20px rgba(0,0,0,.05); }

  /* CTA BUTTON */
  .cta-box { text-align: center; margin: 50px 0; }
  .btn-admission {
    padding:14px 28px; font-size:18px; font-weight: 900;
    background: linear-gradient(90deg, var(--fse-blue), var(--fse-sky));
    color:#fff; border:0; border-radius: 12px;
    cursor:pointer; box-shadow: 0 8px 24px rgba(31,100,200,.25);
  }
  .btn-admission:hover { transform: translateY(-3px); box-shadow: 0 12px 28px rgba(31,100,200,.35); }
</style>

@section('content')

{{-- NEW HERO (NO SLIDER) --}}
<section class="hero">
  <h1>Welcome to AL-FARAN School of Excellence</h1>
  <p>Shaping tomorrow’s leaders through academics, values & creativity.</p>
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

  {{-- CTA --}}
  <div class="cta-box">
    <a href="{{ route('admission') }}">
      <button class="btn-admission"> Get Admission</button>
    </a>
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

</main>
@endsection
