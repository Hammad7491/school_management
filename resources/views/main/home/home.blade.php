{{-- resources/views/main/home/home.blade.php --}}
@extends('main.layouts.app')

@section('title', 'Home — AL-FARAN School of Excellence')
@section('body_class', 'page--home')


<style>
  :root{
    --ink:#0b1020; --muted:#59657a; --stroke:rgba(15,23,42,.12);
    --brand1:#6a7bff; --brand2:#22d3ee;
  }

  body{background:#f8fafc;}

  /* ====== FULL-WIDTH HERO SLIDER ====== */
  .hero{
    width:100%; margin:0; padding:0;
    overflow:hidden; position:relative;
  }
  .hero__view{position:relative; overflow:hidden;}
  .slides{display:flex; width:300%; transition:transform .7s ease;}
  .slide{min-width:100%; height:420px;}
  .slide img{width:100%; height:100%; object-fit:cover; display:block;}

  /* overlay text */
  .hero__caption{
    position:absolute; inset:0;
    display:flex; align-items:center; justify-content:center;
    flex-direction:column; text-align:center;
    background:rgba(0,0,0,.35); color:#fff; padding:20px;
  }
  .hero__caption h1{font-size:32px; font-weight:800; margin-bottom:8px;}
  .hero__caption p{font-size:15px; max-width:700px; line-height:1.55;}

  .dots{display:flex; gap:6px; justify-content:center; position:absolute; bottom:15px; left:0; right:0;}
  .dot{width:10px; height:10px; border-radius:50%; background:#d1d5db; cursor:pointer;}
  .dot.active{background:linear-gradient(90deg,var(--brand1),var(--brand2));}

  /* ====== CONTENT WRAPPER ====== */
  .wrap{max-width:1100px; margin:0 auto; padding:30px 16px;}

  .h-title{font-size:22px; font-weight:800; color:var(--ink); margin:10px 0;}
  .h-sub{font-size:14px; color:var(--muted); line-height:1.55; margin:0 auto;}

  /* ====== FORM ====== */
  .form-card{
    background:#fff; border:1px solid var(--stroke); border-radius:12px;
    padding:16px; box-shadow:0 6px 18px rgba(0,0,0,.05);
  }
  .grid{display:grid; gap:10px;}
  .grid-2{grid-template-columns:1fr 1fr;}
  .label{font-size:13px; font-weight:700; margin-bottom:4px; display:block;}
  .control{width:100%; padding:9px 10px; border:1px solid #cfd5e1; border-radius:8px; font-size:14px;}
  .btn{padding:10px 14px; border-radius:8px; font-weight:700; border:0;
    background:linear-gradient(90deg,var(--brand1),var(--brand2)); color:#fff; cursor:pointer;}

  /* ====== GALLERY ====== */
  .g-grid{display:grid; gap:10px; grid-template-columns:repeat(auto-fit,minmax(220px,1fr));}
  .g-grid img{width:100%; height:160px; object-fit:cover; border-radius:8px;}

  /* Responsive */
  @media (max-width:920px){.grid-2{grid-template-columns:1fr;}.slide{height:320px;}}
  @media (max-width:520px){.slide{height:240px;}.hero__caption h1{font-size:22px;}}
</style>


@section('content')
  {{-- ===== HERO SLIDER ===== --}}
  <section class="hero" aria-label="Hero banners">
    <div class="hero__view">
      <div class="slides" id="heroSlides">
        <div class="slide">
          <img src="{{ asset('assets/images/banner1.jpg') }}" alt="Banner 1">
          <div class="hero__caption">
            <h1>Welcome to AL-FARAN</h1>
            <p>Shaping tomorrow’s leaders through academics, values & creativity.</p>
          </div>
        </div>
        <div class="slide">
          <img src="{{ asset('assets/images/banner2.jpg') }}" alt="Banner 2">
          <div class="hero__caption">
            <h1>Safe & Caring Campus</h1>
            <p>Every child thrives in a nurturing, supportive environment.</p>
          </div>
        </div>
        <div class="slide">
          <img src="{{ asset('assets/images/banner3.jpg') }}" alt="Banner 3">
          <div class="hero__caption">
            <h1>Holistic Development</h1>
            <p>Beyond classrooms — arts, sports, leadership & more.</p>
          </div>
        </div>
      </div>
      <div class="dots" id="heroDots">
        <span class="dot active" data-i="0"></span>
        <span class="dot" data-i="1"></span>
        <span class="dot" data-i="2"></span>
      </div>
    </div>
  </section>

  <main class="wrap">
    {{-- ABOUT --}}
    <section class="about" aria-label="About">
      <h2 class="h-title">About Our School</h2>
      <p class="h-sub">
        AL-FARAN School of Excellence blends strong academics with values and creativity.
        With caring teachers and a vibrant campus, we prepare students not just for exams,
        but for life.
      </p>
    </section>

    {{-- ADMISSION FORM --}}
    <section class="admission" aria-label="Admission form" style="margin-top:30px;">
      <h2 class="h-title">Admission Form</h2>
      <div class="form-card">
        <form onsubmit="return false">
          <div class="grid grid-2">
            <div><label class="label">Student Name</label><input type="text" class="control"></div>
            <div><label class="label">Father's Name</label><input type="text" class="control"></div>
            <div><label class="label">Date of Birth</label><input type="date" class="control"></div>
            <div>
              <label class="label">Class</label>
              <select class="control">
                <option disabled selected>Select class</option>
                <option>Nursery</option><option>Prep</option><option>Grade 1</option><option>Grade 2</option>
              </select>
            </div>
          </div>
          <div style="margin-top:10px;">
            <label class="label">Documents (optional)</label>
            <div class="grid grid-2">
              <input type="file" class="control">
              <input type="file" class="control">
              <input type="file" class="control">
            </div>
          </div>
          <div style="margin-top:12px;text-align:right;">
            <button class="btn">Submit</button>
          </div>
        </form>
      </div>
    </section>

    {{-- GALLERY --}}
    <section class="gallery" aria-label="Gallery" style="margin-top:30px;">
      <h2 class="h-title">Campus Life</h2>
      <div class="g-grid">
        <img src="{{ asset('assets/images/school1.jpg') }}" alt="School 1">
        <img src="{{ asset('assets/images/school2.jpg') }}" alt="School 2">
        <img src="{{ asset('assets/images/school3.jpg') }}" alt="School 3">
        <img src="{{ asset('assets/images/school4.jpg') }}" alt="School 4">
      </div>
    </section>

    {{-- ACHIEVEMENTS --}}
    <section class="achievements" aria-label="Achievements" style="margin-top:30px;">
      <h2 class="h-title">Our Achievements</h2>
      <p class="h-sub">
        Board toppers, debate champions, science fair winners, and sports medals —
        AL-FARAN students shine across academics, creativity, and character.
      </p>
    </section>
  </main>
@endsection


<script>
  // Banner slider auto 5s
  (function(){
    const slides = document.getElementById('heroSlides');
    const dots = document.querySelectorAll('#heroDots .dot');
    let i = 0;
    function go(n){
      i = (n + dots.length) % dots.length;
      slides.style.transform = `translateX(-${i*100}%)`;
      dots.forEach(d=>d.classList.remove('active'));
      dots[i].classList.add('active');
    }
    dots.forEach(d => d.addEventListener('click', () => go(+d.dataset.i)));
    setInterval(()=>go(i+1), 5000);
  })();
</script>

