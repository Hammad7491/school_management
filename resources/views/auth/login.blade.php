<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | AL-FARAN School of Excellence</title>
  <link rel="icon" type="image/png" href="{{ asset('assets/images/school/logo.jpg') }}" sizes="32x32" />

  <!-- remix icon -->
  <link rel="stylesheet" href="{{ asset('assets/css/remixicon.css') }}" />
  <!-- Bootstrap -->
  <link rel="stylesheet" href="{{ asset('assets/css/lib/bootstrap.min.css') }}" />
  <!-- Main CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />

  <style>
    body {
      min-height: 100vh;
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #6a7bff, #22d3ee);
      font-family: 'Inter', sans-serif;
      padding: 20px;
    }

    .login-wrapper {
      width: 100%;
      max-width: 420px;
      background: linear-gradient(135deg, #6a7bff, #22d3ee);
      border-radius: 20px;
      padding: 40px 32px;
      box-shadow: 0 20px 50px rgba(0,0,0,0.25);
      text-align: center;
      color: #fff;
      animation: fadeIn 0.6s ease-in-out;
    }

    @keyframes fadeIn {
      from {opacity: 0; transform: translateY(20px);}
      to {opacity: 1; transform: translateY(0);}
    }

    .login-logo {
      margin-bottom: 20px;
    }
    .login-logo img {
      width: 100px;
      height: 100px;
      object-fit: contain;
      border-radius: 12px;
      border: 2px solid #fff;
      background: #fff;
      padding: 8px;
      box-shadow: 0 6px 16px rgba(0,0,0,0.15);
    }

    .login-title {
      font-size: 18px;
      font-weight: 600;
      margin-bottom: 25px;
      line-height: 1.5;
    }

    .form-control {
      border-radius: 12px;
      padding: 14px 16px;
      font-size: 15px;
      border: none;
      margin-bottom: 15px;
    }

    .btn-primary {
      background: #fff;
      color: #0b1020;
      border: none;
      font-weight: 700;
      padding: 14px;
      border-radius: 12px;
      font-size: 16px;
      margin-bottom: 15px;
      transition: 0.2s;
    }
    .btn-primary:hover { background: #f1f5f9; }

    .quick-buttons button {
      flex: 1;
      min-width: 45%;
      border-radius: 10px;
      font-weight: 600;
      background: rgba(255,255,255,0.15);
      color: #fff;
      border: 1px solid rgba(255,255,255,0.4);
    }
    .quick-buttons button:hover {
      background: rgba(255,255,255,0.25);
    }

    .btn-home {
      position: absolute;
      top: 20px;
      left: 20px;
      background: rgba(255,255,255,0.2);
      color: #fff;
      padding: 8px 16px;
      border-radius: 12px;
      font-weight: 600;
      text-decoration: none;
      transition: 0.2s;
    }
    .btn-home:hover { background: rgba(255,255,255,0.35); }

    @media(max-width: 500px){
      .login-wrapper { padding: 30px 20px; }
      .login-title { font-size: 16px; }
    }
  </style>
</head>
<body>

<a href="{{ url('/') }}" class="btn-home"><i class="ri-home-4-line"></i> Home</a>

<div class="login-wrapper">
  <div class="login-logo">
    <img src="{{ asset('assets/images/school/logo.jpg') }}" alt="School Logo">
  </div>

  <div class="login-title">
    Login as Faculty or Student<br>
    in <strong>AL-FARAN School of Excellence</strong>
  </div>

  @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show text-start" role="alert">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <form id="loginForm" action="{{ route('login') }}" method="POST">
    @csrf

    <input type="email" name="email" class="form-control" placeholder="Email address" value="{{ old('email') }}" required>
    <div class="position-relative">
      <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
      <button type="button" id="togglePassword" class="position-absolute end-0 top-50 translate-middle-y me-3 bg-transparent border-0 text-dark">
        <i class="ri-eye-line"></i>
      </button>
    </div>

    <button type="submit" class="btn btn-primary w-100">Log In</button>

    {{-- Quick login buttons --}}
    <div class="d-flex flex-wrap gap-2 quick-buttons">
      <button type="button" onclick="fillLogin('a@a','a')">Admin</button>
      <button type="button" onclick="fillLogin('principal@example.com','password')">Principal</button>
      <button type="button" onclick="fillLogin('teacher@example.com','password')">Teacher</button>
      <button type="button" onclick="fillLogin('student@example.com','password')">Student</button>
    </div>
  </form>
</div>

<!-- JS -->
<script src="{{ asset('assets/js/lib/bootstrap.bundle.min.js') }}"></script>
<script>
  function fillLogin(email, password) {
    document.querySelector('[name="email"]').value = email;
    document.querySelector('[name="password"]').value = password;
    document.getElementById('loginForm').submit();
  }

  document.getElementById('togglePassword').addEventListener('click', function() {
    const pwd = document.getElementById('password');
    const icon = this.querySelector('i');
    pwd.type = pwd.type === 'password' ? 'text' : 'password';
    icon.classList.toggle('ri-eye-line');
    icon.classList.toggle('ri-eye-off-line');
  });
</script>
</body>
</html>
