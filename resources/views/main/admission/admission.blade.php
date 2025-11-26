{{-- resources/views/main/admission/admission.blade.php --}}
@extends('main.layouts.app')

@section('title', 'Admission — AL-FARAN School of Excellence')
@section('body_class', 'page--admission')

@push('styles')
<style>
  :root{
    --fse-blue:#1f64c8;
    --fse-sky:#22c1f1;
    --ink:#0b1020;
    --muted:#5b6479;
    --bg:#f7fafc;
    --card:#ffffff;
    --stroke: rgba(12,18,38,.1);
    --radius:14px;
  }

  body{ background:var(--bg); margin:0; color:var(--ink); }

  /* HERO */
  .hero{
    background:linear-gradient(135deg,var(--fse-blue),var(--fse-sky));
    color:#fff;
    padding:100px 20px;
    text-align:center;
  }
  .hero h1{
    font-size: clamp(32px, 5vw, 56px);
    font-weight:900;
    margin:0;
    text-shadow:0 4px 14px rgba(0,0,0,.25);
  }

  /* ENROL STEPS */
  .steps{
    max-width:1000px;
    margin:60px auto;
    text-align:center;
    padding:0 20px;
  }
  .steps h2{
    font-weight:900;
    font-size:clamp(22px, 3vw, 28px);
    margin-bottom:40px;
    background:linear-gradient(90deg,var(--fse-blue),var(--fse-sky));
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
  }
  .steps-line{
    display:flex;
    justify-content:center;
    align-items:center;
    gap:60px;
    flex-wrap:wrap;
    position:relative;
  }
  .step{
    text-align:center;
    flex:1 1 200px;
    z-index:2;
  }
  .step .icon{
    height:100px;
    width:100px;
    border-radius:50%;
    background:var(--fse-blue);
    display:flex;
    align-items:center;
    justify-content:center;
    margin:0 auto 16px;
    color:#fff;
    font-size:42px;
    box-shadow:0 6px 20px rgba(31,100,200,.25);
  }
  .step h3{
    font-size:18px;
    font-weight:800;
    margin:0;
    color:var(--ink);
  }
  .steps-line::before{
    content:"";
    position:absolute;
    top:50px;
    left:15%;
    right:15%;
    border-top:3px dashed #cfd8e3;
    z-index:1;
  }

  /* FORM SECTION */
  .form-section{
    max-width:800px;
    margin:60px auto;
    background:var(--card);
    padding:30px;
    border-radius:var(--radius);
    box-shadow:0 8px 28px rgba(0,0,0,.08);
  }
  .form-section h2{
    font-weight:900;
    font-size:clamp(22px, 3vw, 28px);
    margin-bottom:20px;
    text-align:center;
    background:linear-gradient(90deg,var(--fse-blue),var(--fse-sky));
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
  }
  .form-control{
    width:100%;
    padding:12px 14px;
    margin-bottom:16px;
    border:1px solid var(--stroke);
    border-radius:var(--radius);
  }
  .form-control:focus{
    outline:none;
    border-color:var(--fse-blue);
    box-shadow:0 0 0 3px rgba(31,100,200,.2);
  }
  .btn-submit{
    display:block;
    width:100%;
    padding:14px;
    background:linear-gradient(90deg,var(--fse-blue),var(--fse-sky));
    color:#fff;
    font-weight:900;
    border:none;
    border-radius:var(--radius);
    cursor:pointer;
    transition:.2s;
  }
  .btn-submit:hover{ box-shadow:0 6px 20px rgba(31,100,200,.25); }

  .note{
    margin-top:20px;
    text-align:center;
    font-size:15px;
    color:var(--muted);
    font-weight:600;
  }

  /* SUCCESS ALERT */
  .form-alert{
    margin-bottom:20px;
    padding:12px 14px;
    border-radius:var(--radius);
    background:#ecfdf3;
    border:1px solid rgba(34,197,94,.3);
    color:#166534;
    font-weight:600;
    display:flex;
    align-items:flex-start;
    gap:10px;
  }
  .form-alert i{
    margin-top:2px;
  }
</style>
@endpush

@section('content')
  {{-- HERO --}}
  <section class="hero">
    <h1>Admission Now</h1>
  </section>

  {{-- STEPS --}}
  <section class="steps">
    <h2>Enrol in 2 Easy Steps</h2>
    <div class="steps-line">
      <div class="step">
        <div class="icon"><i class="fa-solid fa-file-pen"></i></div>
        <h3>Registration</h3>
      </div>
      <div class="step">
        <div class="icon"><i class="fa-solid fa-folder-open"></i></div>
        <h3>Documentation</h3>
      </div>
    </div>
  </section>

  {{-- FORM --}}
  <section class="form-section">
    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
      <div class="form-alert">
        <i class="fa-solid fa-circle-check"></i>
        <span>{{ session('success') }}</span>
      </div>
    @endif

    <h2>Registration Form</h2>
    <form action="{{ route('admission.store') }}" method="POST">
      @csrf
      <input type="text" name="student_name" class="form-control" placeholder="Student Name" required>
      <select name="gender" class="form-control" required>
        <option value="">Select Gender</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
      </select>
      <input type="text" name="school_name" class="form-control" value="Al-Faran School of Excellence" readonly>
      <select name="class" class="form-control" required>
        <option value="">Select Class</option>
        <option>Montessori</option>
        <option>Prep</option>
        <option>Class 1</option>
        <option>Class 2</option>
        <option>Class 3</option>
        <option>Class 4</option>
        <option>Class 5</option>
        <option>Class 6</option>
        <option>Class 7</option>
        <option>Class 8</option>
        <option>Class 9</option>
        <option>Class 10</option>
      </select>
      <input type="text" name="parent_name" class="form-control" placeholder="Parent Name" required>
      <input type="tel" name="parent_contact" class="form-control" placeholder="Parent Contact Number" required>
      <input type="email" name="parent_email" class="form-control" placeholder="Parent Email" required>
      <button type="submit" class="btn-submit">Submit Information</button>
    </form>

    <p class="note">
      <i class="fa-solid fa-circle-info"></i>
      After completing the registration process, a campus representative will contact you directly.
    </p>
  </section>
@endsection
