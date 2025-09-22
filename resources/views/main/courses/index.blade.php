@extends('main.layouts.app')

@section('title', 'Courses — AL-FARAN School of Excellence')
@section('body_class', 'page--courses')

@section('content')
<style>
  :root{
    --bg:#f7f9ff; --ink:#0b1020; --muted:#6b7280; --card:#ffffff;
    --stroke:rgba(15,23,42,.10); --brand1:#6a7bff; --brand2:#22d3ee;
    --radius:18px;
  }
  @media (prefers-color-scheme: dark){
    :root{ --bg:#0b1020; --ink:#e6e9f5; --muted:#9aa3ba; --card:#0f1830;
      --stroke:rgba(255,255,255,.12);}
  }

  body.page--courses .dashboard-main{
    padding-left:0 !important; margin-left:0 !important;
  }
  body.page--courses .page-container,
  body.page--courses .container,
  body.page--courses .content{
    padding-left:0 !important; margin-left:0 !important;
  }

  .page{ background:var(--bg); color:var(--ink); }

  /* Hero */
  .hero{
    background:linear-gradient(135deg,var(--brand1),var(--brand2));
    padding:90px 14px; text-align:center; color:#fff;
  }
  .hero h1{ font-size:clamp(32px,6vw,64px); font-weight:900; margin-bottom:12px; }
  .hero p{ max-width:720px; margin:0 auto; font-size:18px; opacity:.95; }

  /* Section headings */
  .wrap{ max-width:1200px; margin:0 auto; padding:50px 18px; }
  h2.section-title{
    font-size:clamp(26px,4vw,40px); font-weight:900; margin:40px 0 28px;
    background:linear-gradient(90deg,var(--brand1),var(--brand2));
    -webkit-background-clip:text; -webkit-text-fill-color:transparent;
  }

  /* Course cards */
  .grid{ display:grid; gap:26px; }
  @media(min-width:768px){ .grid{ grid-template-columns:repeat(2,1fr);} }
  @media(min-width:1100px){ .grid{ grid-template-columns:repeat(3,1fr);} }

  .card{
    background:var(--card); border:1px solid var(--stroke);
    border-radius:var(--radius); overflow:hidden;
    box-shadow:0 12px 28px rgba(2,6,23,.06);
    display:flex; flex-direction:column;
  }
  .card img{ width:100%; height:200px; object-fit:cover; }
  .card-body{ padding:20px; flex:1; display:flex; flex-direction:column; }
  .card-body h3{ font-size:20px; font-weight:800; margin-bottom:10px; color:var(--brand1);}
  .card-body p{ font-size:15px; line-height:1.6; color:var(--ink); flex:1; }
</style>

<div class="page">

  <!-- Hero -->
  <section class="hero">
    <h1>Our Courses</h1>
    <p>Explore computer and digital marketing courses designed to give you skills for the future.</p>
  </section>

  <!-- Computer Courses -->
  <div class="wrap">
    <h2 class="section-title">Computer Courses</h2>
    <div class="grid">
      <div class="card">
        <img src="{{ asset('assets/images/school/web.png') }}" alt="Web Development">
        <div class="card-body">
          <h3>Web Development</h3>
          <p>Learn how to build modern websites and web applications using HTML, CSS, JavaScript, and frameworks.</p>
        </div>
      </div>
      <div class="card">
        <img src="{{ asset('assets/images/school/graphic.png') }}" alt="Graphic Designing">
        <div class="card-body">
          <h3>Graphic Designing</h3>
          <p>Master tools like Photoshop and Illustrator to create stunning visuals, branding, and digital designs.</p>
        </div>
      </div>
      <div class="card">
        <img src="{{ asset('assets/images/school/video.png') }}" alt="Video Editing">
        <div class="card-body">
          <h3>Video Editing</h3>
          <p>Enhance your storytelling skills by learning video editing techniques using industry-standard software.</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Digital Marketing Courses -->
  <div class="wrap">
    <h2 class="section-title">Digital Marketing Courses</h2>
    <div class="grid">
      <div class="card">
        <img src="{{ asset('assets/images/school/seo.png') }}" alt="SEO">
        <div class="card-body">
          <h3>SEO (Search Engine Optimization)</h3>
          <p>Understand search engine algorithms and optimize websites to improve visibility and ranking on Google.</p>
        </div>
      </div>
      <div class="card">
        <img src="{{ asset('assets/images/school/social.png') }}" alt="Social Media Marketing">
        <div class="card-body">
          <h3>Social Media Marketing</h3>
          <p>Learn strategies to grow brands and businesses on platforms like Facebook, Instagram, and Twitter.</p>
        </div>
      </div>
      <div class="card">
        <img src="{{ asset('assets/images/school/facebook.png') }}" alt="Facebook Marketing">
        <div class="card-body">
          <h3>Facebook Marketing</h3>
          <p>Run effective ad campaigns, grow your page, and engage audiences through targeted Facebook marketing.</p>
        </div>
      </div>
      <div class="card">
        <img src="{{ asset('assets/images/school/insta.png') }}" alt="Instagram Marketing">
        <div class="card-body">
          <h3>Instagram Marketing</h3>
          <p>Use Instagram reels, ads, and content strategies to build followers and convert them into customers.</p>
        </div>
      </div>
      <div class="card">
        <img src="{{ asset('assets/images/school/content.png') }}" alt="Content Writing">
        <div class="card-body">
          <h3>Content Writing</h3>
          <p>Develop engaging blog posts, articles, and copywriting skills to captivate your readers.</p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
