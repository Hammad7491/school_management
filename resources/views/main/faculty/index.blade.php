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
    :root{ --bg:#0b1020; --ink:#e6e9f5; --muted:#9aa3ba; --card:#0f1830;
      --stroke:rgba(255,255,255,.12);}
  }

  body.page--faculty .dashboard-main{ padding-left:0 !important; margin-left:0 !important; }
  body.page--faculty .page-container{ padding-left:0 !important; margin-left:0 !important; }

  .page{ background:var(--bg); color:var(--ink); }

  /* Hero */
  .hero{ background:linear-gradient(135deg,var(--brand1),var(--brand2));
    padding:90px 14px; text-align:center; color:#fff; }
  .hero h1{ font-size:clamp(32px,6vw,64px); font-weight:900; margin-bottom:12px; }
  .hero p{ max-width:720px; margin:0 auto; font-size:18px; opacity:.95; }

  /* Faculty grid */
  .wrap{ max-width:1200px; margin:0 auto; padding:50px 18px; }
  h2.section-title{
    font-size:clamp(26px,4vw,40px); font-weight:900; margin:40px 0 28px;
    background:linear-gradient(90deg,var(--brand1),var(--brand2));
    -webkit-background-clip:text; -webkit-text-fill-color:transparent;
    text-align:center;
  }

  .grid{ display:grid; gap:32px; }
  @media(min-width:600px){ .grid{ grid-template-columns:repeat(2,1fr);} }
  @media(min-width:1000px){ .grid{ grid-template-columns:repeat(3,1fr);} }

  .card{ background:var(--card); border:1px solid var(--stroke);
    border-radius:var(--radius); padding:36px 24px; text-align:center;
    box-shadow:0 6px 16px rgba(2,6,23,.08); transition:.3s ease; }
  .card:hover{ transform:translateY(-6px) scale(1.02);
    box-shadow:0 14px 30px rgba(2,6,23,.12); }

  .card img{ width:160px; height:160px; object-fit:cover;
    border-radius:50%; border:4px solid var(--brand1); margin-bottom:18px;
    box-shadow:0 6px 18px rgba(0,0,0,.08); }
  .card h3{ font-size:20px; font-weight:800; margin-bottom:10px; color:var(--ink); }
  .card p{ font-size:15px; line-height:1.6; color:var(--muted); }
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
        <p>BCS Computer Science (UP)<br>
          Computer Hardware & Software Engineering (Wisdom College)<br>
          Artificial Intelligence & Robotics
        </p>
      </div>

      <div class="card">
        <img src="{{ asset('assets/images/school/nazim.jpg') }}" alt="Nazim Ali">
        <h3>Nazim Ali</h3>
        <p>BS (Bio Chemistry)</p>
      </div>

      <div class="card">
        <img src="{{ asset('assets/images/school/girl.png') }}" alt="Miss Saira Mudasser Alvi">
        <h3>Miss Saira Mudasser Alvi</h3>
        <p>M.A (English), B.Ed<br>BS(Hons.) Bio Chemistry</p>
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
        <img src="{{ asset('assets/images/school/girl.png') }}" alt="Miss Rabia">
        <h3>Miss Rabia</h3>
        <p>—</p>
      </div>

      <div class="card">
        <img src="{{ asset('assets/images/school/girl.png') }}" alt="Miss Sobia Hamid">
        <h3>Miss Sobia Hamid</h3>
        <p>M.A (Urdu)<br>BS(Hons.) Math</p>
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
