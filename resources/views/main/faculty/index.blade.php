@extends('main.layouts.app')

@section('title', 'Faculty — AL-FARAN School of Excellence')
@section('body_class', 'page--faculty')

@section('content')
<style>
  :root{
    --bg:#f7f9ff; --ink:#0b1020; --muted:#6b7280; --card:#ffffff;
    --stroke:rgba(15,23,42,.10); --brand1:#6a7bff; --brand2:#22d3ee;
    --radius:20px;
  }
  @media(prefers-color-scheme: dark){
    :root{
      --bg:#0b1020; --ink:#e6e9f5; --muted:#9aa3ba; --card:#0f1830;
      --stroke:rgba(255,255,255,.12);
    }
  }

  body.page--faculty .dashboard-main{ padding-left:0 !important; margin-left:0 !important; }
  body.page--faculty .page-container{ padding-left:0 !important; margin-left:0 !important; }

  .page{ background:var(--bg); color:var(--ink); }

  /* Hero */
  .hero{
    background:linear-gradient(135deg,var(--brand1),var(--brand2));
    padding:clamp(48px,8vw,78px) 14px;
    text-align:center; color:#fff;
  }
  .hero h1{
    font-size:clamp(28px,5vw,54px);
    font-weight:900;
    margin:0 0 10px 0;
  }
  .hero p{
    max-width:720px;
    margin:0 auto;
    font-size:clamp(14px,2vw,17px);
    opacity:.95;
    padding:0 6px;
  }

  /* Content */
  .wrap{ max-width:1200px; margin:0 auto; padding:clamp(22px,5vw,44px) 18px; }

  /* section heading smaller + clean */
  h2.section-title{
    font-size:clamp(20px,3.2vw,32px);
    font-weight:900;
    margin:34px 0 22px;
    background:linear-gradient(90deg,var(--brand1),var(--brand2));
    -webkit-background-clip:text; -webkit-text-fill-color:transparent;
    text-align:center;
  }

  .grid{
    display:grid;
    gap:clamp(14px,2.6vw,26px);
    grid-template-columns:repeat(auto-fit, minmax(240px, 1fr));
    align-items:start; /* IMPORTANT: cards won't stretch to tallest card height */
  }

  .card{
    background:var(--card);
    border:1px solid var(--stroke);
    border-radius:var(--radius);
    padding:22px 18px; /* less height */
    text-align:center;
    box-shadow:0 6px 16px rgba(2,6,23,.08);
    transition:.25s ease;
    height:auto;
  }
  .card:hover{
    transform:translateY(-4px);
    box-shadow:0 14px 30px rgba(2,6,23,.12);
  }

  .card img{
    width:clamp(110px,22vw,140px);
    height:clamp(110px,22vw,140px);
    object-fit:cover;
    border-radius:50%;
    border:4px solid var(--brand1);
    margin:0 0 14px 0;
    box-shadow:0 6px 18px rgba(0,0,0,.08);
  }

  /* make card name smaller (force override theme styles) */
  body.page--faculty .card h3{
    font-size:clamp(18px,2.1vw,22px) !important;
    font-weight:850 !important;
    line-height:1.2 !important;
    margin:0 0 8px 0 !important;
    color:var(--ink) !important;
    letter-spacing:.2px;
  }

  body.page--faculty .card p{
    font-size:14px !important;
    line-height:1.55 !important;
    color:var(--muted) !important;
    margin:0 !important;
  }

  @media(max-width:480px){
    .card{ padding:18px 14px; }
    .card img{ margin-bottom:12px; }
  }
</style>

<div class="page">

  <!-- Hero -->
  <section class="hero">
    <h1>Our Faculty</h1>
    <p>Meet our dedicated teachers and mentors shaping the future of students.</p>
  </section>

  <!-- Main Faculty -->
  <div class="wrap">
    <h2 class="section-title">Teaching Faculty</h2>
    <div class="grid">

      <!-- Mr Ahmad FIRST -->
      <div class="card">
        <img src="{{ asset('assets/images/school/ahmad.jpg') }}" alt="Mr. Muhammad Ahmad Awan">
        <h3>Eng. Ahmad Awan</h3>
        <p>
          BCS Computer Science (UP)<br>
          Computer Hardware & Software Engineering (Wisdom College)<br>
          Artificial Intelligence & Robotics
        </p>
      </div>

      <div class="card">
        <img src="{{ asset('assets/images/school/nazim.jpg') }}" alt="Nazim Ali">
        <h3>Nazim Ali</h3>
        <p>BS (Bio Chemistry)</p>
      </div>

      <!-- Tasleem Qamar: male emoji + male image -->
      <div class="card">
        <img src="{{ asset('assets/images/school/boy.png') }}" alt="Tasleem Qamar">
        <h3>Tasleem Qamar</h3>
        <p>BS (Physics)</p>
      </div>

      <div class="card">
        <img src="{{ asset('assets/images/school/girl.png') }}" alt="Fazeelat Iqbal">
        <h3>Fazeelat Iqbal</h3>
        <p>BS (Botany)</p>
      </div>

      <div class="card">
        <img src="{{ asset('assets/images/school/girl.png') }}" alt="Miss Saira Mudasser Alvi">
        <h3>Miss Saira Mudasser Alvi</h3>
        <p>M.A (English), B.Ed</p>
      </div>

      <div class="card">
        <img src="{{ asset('assets/images/school/girl.png') }}" alt="Miss Hina">
        <h3>Miss Hina</h3>
        <p>M.Sc Economics<br>B.Sc Computer, Math, Economics</p>
      </div>

      <div class="card">
        <img src="{{ asset('assets/images/school/girl.png') }}" alt="Miss Aneesa">
        <h3>Miss Aneesa</h3>
        <p>BS(Hons.) Zoology</p>
      </div>

      <div class="card">
        <img src="{{ asset('assets/images/school/girl.png') }}" alt="Miss Sobia Hamid">
        <h3>Miss Sobia Hamid</h3>
        <p>M.A (Urdu), B.ED</p>
      </div>

      <div class="card">
        <img src="{{ asset('assets/images/school/girl.png') }}" alt="Miss Shakeela">
        <h3>Miss Shakeela</h3>
        <p>B.A</p>
      </div>

    </div>
  </div>

  <!-- Afternoon Coaching Staff -->
  <div class="wrap">
    <h2 class="section-title">Afternoon Coaching Staff</h2>
    <div class="grid">

      <div class="card">
        <img src="{{ asset('assets/images/school/boy.png') }}" alt="Muhammad Shafiq">
        <h3>Muhammad Shafiq</h3>
        <p>M.Phil (Physics), B.Ed</p>
      </div>

      <div class="card">
        <img src="{{ asset('assets/images/school/boy.png') }}" alt="Muhammad Farman Khan">
        <h3>Muhammad Farman Khan</h3>
        <p>M.Phil (English), B.Ed</p>
      </div>

      <div class="card">
        <img src="{{ asset('assets/images/school/boy.png') }}" alt="Aqeel Ahmad">
        <h3>Aqeel Ahmad</h3>
        <p>BS(Hons.) Zoology</p>
      </div>

    </div>
  </div>

</div>
@endsection
