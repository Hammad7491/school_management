{{-- resources/views/main/fee/index.blade.php --}}
@extends('main.layouts.app')

@section('title', 'Fee Structure — AL-FARAN School of Excellence')
@section('body_class', 'page--fee')

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
  body{ 
    background: var(--bg); 
    color: var(--ink); 
    margin:0; 
    padding:0;
  }

  /* HERO */
  .hero{
    position: relative;
    width: 100%;
    margin: 0;
    height: clamp(260px, 40vw, 420px);
    overflow:hidden;
    display:flex;
    align-items:center;
    justify-content:center;
  }
  .hero img{
    width:100%;
    height:100%;
    object-fit:cover;
    display:block;
  }
  .hero__caption{
    position:absolute;
    inset:0;
    display:flex;
    align-items:center;
    justify-content:center;
    text-align:center;
    background:rgba(0,0,0,.45);
  }
  .hero__caption h1{
    font-size: clamp(26px, 4vw, 44px);
    font-weight:900;
    margin:0;
    background: linear-gradient(90deg, var(--fse-blue), var(--fse-sky));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    text-shadow:0 3px 12px rgba(0,0,0,.6);
  }

  /* CONTENT */
  .wrap{
    width:100%;       /* ✅ full width */
    margin:0;         /* ✅ remove left gap */
    padding:40px 20px;
    box-sizing:border-box;
  }
  .h-title{
    font-size: clamp(22px, 2.8vw, 36px);
    font-weight: 900;
    text-transform: uppercase;
    margin: 0 0 24px 0;
    background: linear-gradient(90deg, var(--fse-blue), var(--fse-sky));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    display:inline-block;
  }
  .h-title::after{
    content:"";
    display:block;
    width:80px;
    height:5px;
    margin-top:8px;
    border-radius:999px;
    background: linear-gradient(90deg, var(--fse-blue), var(--fse-sky));
  }

  /* FEE TABLE */
  .fee-table{
    width:100%;
    border-collapse:collapse;
    background:var(--card);
    border-radius: var(--radius);
    overflow:hidden;
    box-shadow:0 8px 24px rgba(0,0,0,.08);
  }
  .fee-table thead{
    background:linear-gradient(90deg, var(--fse-blue), var(--fse-sky));
    color:#fff;
  }
  .fee-table th, .fee-table td{
    padding:14px 18px;
    text-align:center;
    font-size:16px;
    border-bottom:1px solid var(--stroke);
  }
  .fee-table th{ font-weight:900; text-transform:uppercase; }
  .fee-table tr:last-child td{ border-bottom:0; }
  .fee-table tbody tr:nth-child(even){ background:#f9fbff; }

  @media(max-width:600px){
    .fee-table th, .fee-table td{ padding:10px 8px; font-size:14px; }
  }
</style>

@section('content')
  {{-- HERO --}}
  <section class="hero">
    <img src="{{ asset('assets/images/school/fee.png') }}" alt="Fee Structure">
    <div class="hero__caption">
      
    </div>
  </section>

  <main class="wrap">
    <h2 class="h-title">Our Fee Plan</h2>
    <table class="fee-table">
      <thead>
        <tr>
          <th>Class</th>
          <th>Monthly Fee (PKR)</th>
        </tr>
      </thead>
      <tbody>
        <tr><td>Montessori</td><td>1500</td></tr>
        <tr><td>Prep</td><td>2000</td></tr>
        <tr><td>Class 1</td><td>2500</td></tr>
        <tr><td>Class 2</td><td>3000</td></tr>
        <tr><td>Class 3</td><td>3500</td></tr>
        <tr><td>Class 4</td><td>4000</td></tr>
        <tr><td>Class 5</td><td>4500</td></tr>
        <tr><td>Class 6</td><td>5000</td></tr>
        <tr><td>Class 7</td><td>5500</td></tr>
        <tr><td>Class 8</td><td>6000</td></tr>
        <tr><td>Class 9</td><td>6500</td></tr>
        <tr><td>Class 10</td><td>7000</td></tr>
      </tbody>
    </table>
  </main>
@endsection
