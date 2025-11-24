<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | AL-FARAN School of Excellence</title>
  <link rel="icon" type="image/png" href="{{ asset('assets/images/school/logo.jpg') }}" sizes="32x32" />

  <link rel="stylesheet" href="{{ asset('assets/css/remixicon.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/lib/bootstrap.min.css') }}" />

  <style>
    :root {
      --blue: #1f64c8;
      --sky: #22c1f1;
      --ink: #0b1020;
      --muted: #64748b;
      --radius: 16px;
    }

    body {
      margin: 0;
      padding: 20px;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #f0f4ff;
      font-family: "Inter", sans-serif;
    }

    .login-wrapper {
      width: 100%;
      max-width: 420px;
      background: #fff;
      border-radius: var(--radius);
      padding: 40px 32px;
      box-shadow: 0 12px 40px rgba(31, 100, 200, 0.18);
      animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
      from {opacity: 0; transform: translateY(15px);}
      to {opacity: 1; transform: translateY(0);}
    }

    /* LOGO */
    .login-logo img {
      width: 90px;
      height: 90px;
      border-radius: 14px;
      object-fit: cover;
      border: 2px solid #fff;
      background: #fff;
      box-shadow: 0 6px 14px rgba(0,0,0,0.1);
    }

    .login-title {
      margin-top: 14px;
      margin-bottom: 25px;
      line-height: 1.5;
      font-size: 17px;
      color: var(--ink);
      font-weight: 600;
    }

    /* FORM */
    .form-control {
      border-radius: var(--radius);
      padding: 14px 16px;
      border: 1px solid #dbe2ef;
      font-size: 15px;
      margin-bottom: 14px;
    }

    .form-control:focus {
      box-shadow: 0 0 0 3px rgba(31,100,200,0.15);
      border-color: var(--blue);
    }

    /* BUTTON */
    .btn-primary {
      width: 100%;
      background: linear-gradient(90deg, var(--blue), var(--sky));
      border-radius: var(--radius);
      border: none;
      padding: 14px;
      color: #fff;
      font-size: 16px;
      font-weight: 800;
      transition: 0.2s;
    }

    .btn-primary:hover {
      box-shadow: 0 8px 22px rgba(31,100,200,0.3);
      transform: translateY(-2px);
    }

    /* QUICK LOGIN */
    .quick-buttons button {
      flex: 1;
      border-radius: 10px;
      padding: 12px;
      font-size: 14px;
      font-weight: 600;
      background: #f1f5f9;
      border: 1px solid #dbe2ef;
      color: var(--ink);
    }
    .quick-buttons button:hover {
      background: #e2e8f0;
    }

    /* HOME BUTTON */
    .btn-home {
      position: absolute;
      top: 20px;
      left: 20px;
      padding: 8px 16px;
      background: #fff;
      border-radius: 10px;
      font-weight: 700;
      color: var(--blue);
      text-decoration: none;
      border: 1px solid #dce3f5;
      box-shadow: 0 4px 14px rgba(31,100,200,0.15);
    }

    @media(max-width:500px){
      .login-wrapper { padding: 30px 20px; }
    }
  </style>
</head>

<body>

<a href="{{ url('/') }}" class="btn-home"><i class="ri-home-4-line"></i> Home</a>

<div class="login-wrapper">

  <div class="login-logo text-center">
    <img src="{{ asset('assets/images/school/logo.jpg') }}" alt="School Logo">
  </div>

  <div class="login-title text-center">
    Login as Faculty or Student<br>
    <strong>Al-Faran School of Excellence</strong>
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
      <button type="button" id="togglePassword" class="position-absolute end-0 top-50 translate-middle-y me-3 bg-transparent border-0">
        <i class="ri-eye-line"></i>
      </button>
    </div>

    <button type="submit" class="btn btn-primary mt-2">Log In</button>

    <!-- Quick login buttons -->
    

  </form>
</div>

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
