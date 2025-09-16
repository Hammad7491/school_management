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
  .hero{ position:relative; width:100%; overflow:hidden; }
  .slides{ display:flex; width:300%; transition: transform .7s ease; }
  .slide{
    min-width:100%;
    height: clamp(260px, 40vw, 420px);
    position:relative;
    background: linear-gradient(90deg, var(--fse-blue), var(--fse-sky));
  }
  .slide img{ width:100%; height:100%; object-fit:cover; display:block; }
  .slide::after{
    content:""; position:absolute; inset:0; background: rgba(0,0,0,.4); z-index:1;
  }
  .hero__caption{
    position:absolute; inset:0; z-index:2;
    display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center;
    color:#fff; padding: 0 16px;
  }
  .hero__title{
    font-size: clamp(24px, 4.4vw, 44px);
    font-weight: 900;
    background: linear-gradient(90deg, var(--fse-blue), var(--fse-sky));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    margin: 0 0 10px 0;
    text-shadow: 0 3px 18px rgba(0,0,0,.25);
  }
  .hero__sub{ font-size: clamp(13px, 1.6vw, 18px); max-width: 880px; line-height:1.6; margin:0; color:#f1f5f9; }

  .dots{position:absolute; left:0; right:0; bottom:14px; display:flex; justify-content:center; gap:8px; z-index:3;}
  .dot{width:10px; height:10px; border-radius:999px; background:#e5e7eb; cursor:pointer; transition:.2s;}
  .dot.active{ width:26px; background:linear-gradient(90deg,var(--fse-blue),var(--fse-sky)); }

  /* === SECTION HEADINGS === */
  .section{ margin-top: clamp(26px, 5vw, 52px); }
  .h-title{
    font-size: clamp(20px, 2.6vw, 32px);
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: .6px;
    margin: 0 0 14px 0;
    background: linear-gradient(90deg, var(--fse-blue), var(--fse-sky));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    position: relative;
    display:inline-block;
  }
  .h-title::after{
    content:"";
    display:block;
    width:60px; height:4px;
    margin-top:6px;
    border-radius:999px;
    background: linear-gradient(90deg, var(--fse-blue), var(--fse-sky));
  }
  .h-sub{ font-size: clamp(13px, 1.5vw, 16px); color: var(--muted); line-height:1.7; }

  /* === CARDS === */
  .about-card, .form-card{
    background: var(--card); border:1px solid var(--stroke); border-radius: var(--radius);
    padding: 20px; box-shadow: 0 8px 24px rgba(2,6,23,.06);
  }

  /* FORM */
  .grid{ display:grid; gap:12px; }
  .grid-2{ grid-template-columns: repeat(2, minmax(0,1fr)); }
  .label{ font-size: 12px; font-weight: 900; text-transform: uppercase; color: var(--muted); margin-bottom:6px; display:block; }
  .control{
    width:100%; padding:11px 12px; border:1px solid #cfd5e1; border-radius: 10px; font-size:14px; background:#fff;
    transition: border-color .2s, box-shadow .2s;
  }
  .control:focus{ outline: none; border-color: var(--fse-blue); box-shadow: 0 0 0 3px rgba(31,100,200,.18); }
  .btn{
    padding:11px 16px; border-radius: 12px; font-weight: 900; border:0; cursor:pointer;
    background: linear-gradient(90deg, var(--fse-blue), var(--fse-sky)); color:#fff;
    box-shadow: 0 10px 24px rgba(31,100,200,.22);
  }

  /* GALLERY */
  .g-grid{ display:grid; gap:12px; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); }
  .g-card{ position:relative; overflow:hidden; border-radius: 12px; }
  .g-img{ width:100%; aspect-ratio: 4 / 3; object-fit: cover; display:block; transition: transform .5s ease; }
  .g-card:hover .g-img{ transform: scale(1.06); }
  .g-shade{ position:absolute; inset:0; background: linear-gradient(180deg, rgba(0,0,0,0) 40%, rgba(0,0,0,.35)); }
  .g-cap{ position:absolute; left:10px; bottom:8px; color:#fff; font-weight:800; text-shadow:0 2px 10px rgba(0,0,0,.5); }

  /* ACHIEVEMENTS */
  .feat{ display:grid; gap:12px; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); }
  .chip{ background:#fff; border:1px solid var(--stroke); border-radius:12px; padding:14px; box-shadow: 0 6px 20px rgba(0,0,0,.05); }
  .chip__title{ font-weight:900; margin:0 0 4px; color:var(--ink); }
  .chip__text{ margin:0; font-size:14px; color:var(--muted); }
</style>

@section('content')
  {{-- HERO --}}
  <section class="hero">
    <div class="slides" id="heroSlides">
      <div class="slide">
        <img src="{{ asset('assets/images/banner1.jpg') }}" alt="">
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
      <h2 class="h-title">About Our School</h2>
      <div class="about-card">
        <p class="h-sub">AL-FARAN School of Excellence blends strong academics with values and creativity. With caring teachers and a vibrant campus, we prepare students not just for exams, but for life.</p>
      </div>
    </section>

    {{-- FORM --}}
    <section class="section admission">
      <h2 class="h-title">Admission Form</h2>
      <div class="form-card">
        <form onsubmit="return false">
          <div class="grid grid-2">
            <div><label class="label">Student Name</label><input type="text" class="control" placeholder="Enter full name"></div>
            <div><label class="label">Father's Name</label><input type="text" class="control" placeholder="Enter father's name"></div>
            <div><label class="label">Date of Birth</label><input type="date" class="control"></div>
            <div>
              <label class="label">Class</label>
              <select class="control">
                <option disabled selected>Select class</option>
                <option>Nursery</option><option>Prep</option><option>Grade 1</option><option>Grade 2</option>
              </select>
            </div>
          </div>
          <div style="margin-top:12px;">
            <label class="label">Documents (optional)</label>
            <div class="grid grid-2">
              <input type="file" class="control">
              <input type="file" class="control">
              <input type="file" class="control">
            </div>
          </div>
          <div style="margin-top:14px; text-align:right;">
            <button class="btn">Submit</button>
          </div>
        </form>
      </div>
    </section>

    {{-- GALLERY --}}
    <section class="section gallery">
      <h2 class="h-title">Campus Life</h2>
      <div class="g-grid">
        <article class="g-card"><img class="g-img" src="{{ asset('assets/images/school1.jpg') }}"><div class="g-shade"></div><div class="g-cap">Classrooms</div></article>
        <article class="g-card"><img class="g-img" src="{{ asset('assets/images/school2.jpg') }}"><div class="g-shade"></div><div class="g-cap">Library</div></article>
        <article class="g-card"><img class="g-img" src="{{ asset('assets/images/school3.jpg') }}"><div class="g-shade"></div><div class="g-cap">Sports</div></article>
        <article class="g-card"><img class="g-img" src="{{ asset('assets/images/school4.jpg') }}"><div class="g-shade"></div><div class="g-cap">Activities</div></article>
      </div>
    </section>

    {{-- ACHIEVEMENTS --}}
    <section class="section achievements">
      <h2 class="h-title">Our Achievements</h2>
      <div class="feat">
        <div class="chip"><p class="chip__title">Board Toppers</p><p class="chip__text">Strong performance in board exams with top results.</p></div>
        <div class="chip"><p class="chip__title">Debate Champions</p><p class="chip__text">Winners of city-level debates and declamation contests.</p></div>
        <div class="chip"><p class="chip__title">Science & Sports</p><p class="chip__text">Prizes in science fairs, robotics, athletics and more.</p></div>
      </div>
    </section>
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
