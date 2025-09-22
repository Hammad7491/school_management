<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>
        @hasSection('title')@yield('title')@else AL-FARAN — School of Excellence @endif
    </title>

    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}" sizes="16x16" />

    {{-- Core vendor CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/remixicon.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/lib/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/lib/apexcharts.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/lib/dataTables.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/lib/editor-katex.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/lib/editor.atom-one-dark.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/lib/editor.quill.snow.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/lib/flatpickr.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/lib/full-calendar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/lib/jquery-jvectormap-2.0.5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/lib/magnific-popup.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/lib/slick.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/lib/prism.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/lib/file-upload.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/lib/audioplayer.css') }}" />

    {{-- Main theme CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />

    {{-- Font Awesome 6.5.2 --}}
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
          integrity="sha512-Tn2mM6h1k8zNfVvMsu9gmNThXhA4Y2t3qk4kj4V2z6Z5wAv8CfqjJZepF2O0Ub/6r6mZXV9r3HWY3eRq+U9/Dg=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />

    {{-- Page-level styles --}}
    @stack('styles')
    @yield('styles')

    {{-- Layout fixes --}}
    <style>
      :root{
        --ink:#0b1020; --muted:#64748b; --card:#ffffff; --stroke:rgba(15,23,42,.10);
        --brand1:#6a7bff; --brand2:#22d3ee; --ring:rgba(106,123,255,.35);
        --chip:#f2f5ff; --chip-ink:#0b1020;
      }
      @media (prefers-color-scheme: dark){
        :root{
          --ink:#e6e9f5; --muted:#aab3c5; --card:#0f1830; --stroke:rgba(255,255,255,.14);
          --chip:#14223f; --chip-ink:#e6e9f5; --ring:rgba(106,123,255,.55);
        }
      }

      .dashboard-main{
        min-height: 100dvh;
        display: flex;
        flex-direction: column;
        background:
          radial-gradient(1200px 520px at -20% -50%, rgba(106,123,255,.06), transparent 60%),
          radial-gradient(1200px 520px at 120% -40%, rgba(34,211,238,.06), transparent 60%),
          #f7f9fc;
      }

      .page-container{
        flex: 1 1 auto;
      }

      /* Reset left gutter for marketing pages */
      .page--home .dashboard-main,
      .page--fee .dashboard-main,
      .page--admission .dashboard-main,
      .page--vision .dashboard-main {
        padding-left: 0 !important;
        margin-left: 0 !important;
      }

      .page--home .content,
      .page--home .container,
      .page--home .page-container,
      .page--fee .content,
      .page--fee .container,
      .page--fee .page-container,
      .page--admission .content,
      .page--admission .container,
      .page--admission .page-container,
      .page--vision .content,
      .page--vision .container,
      .page--vision .page-container {
        padding-left: 0 !important;
        margin-left: 0 !important;
      }

      /* Centered wrapper */
      .page--home .wrap,
      .page--landing .wrap,
      .page--admission .wrap,
      .page--vision .wrap {
        max-width: 1200px;
        margin: 0 auto;
        padding: 18px 14px 40px;
      }
    </style>
</head>

<body class="@yield('body_class')">
  <main class="dashboard-main">
    {{-- Global Header --}}
    @include('main.layouts.header')

    {{-- Page Content --}}
    <div class="page-container">
      @yield('content')
    </div>

    {{-- Global Footer --}}
    @include('main.layouts.footer')
  </main>

  {{-- Core vendor JS --}}
  <script src="{{ asset('assets/js/lib/jquery-3.7.1.min.js') }}"></script>
  <script src="{{ asset('assets/js/lib/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/js/lib/apexcharts.min.js') }}"></script>
  <script src="{{ asset('assets/js/lib/dataTables.min.js') }}"></script>
  <script src="{{ asset('assets/js/lib/jquery-ui.min.js') }}"></script>
  <script src="{{ asset('assets/js/lib/jquery-jvectormap-2.0.5.min.js') }}"></script>
  <script src="{{ asset('assets/js/lib/jquery-jvectormap-world-mill-en.js') }}"></script>
  <script src="{{ asset('assets/js/lib/magnific-popup.min.js') }}"></script>
  <script src="{{ asset('assets/js/lib/slick.min.js') }}"></script>
  <script src="{{ asset('assets/js/lib/prism.js') }}"></script>
  <script src="{{ asset('assets/js/lib/file-upload.js') }}"></script>
  <script src="{{ asset('assets/js/lib/audioplayer.js') }}"></script>

  {{-- Iconify --}}
  <script src="{{ asset('assets/js/lib/iconify-icon.min.js') }}"></script>

  {{-- Theme/App scripts --}}
  <script src="{{ asset('assets/js/app.js') }}"></script>
  <script src="{{ asset('assets/js/homeOneChart.js') }}"></script>

  {{-- Page-level scripts --}}
  @stack('scripts')
  @yield('scripts')
</body>
</html>
