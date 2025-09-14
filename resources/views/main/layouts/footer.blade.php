{{-- Clean Professional Footer --}}
<footer class="pro-footer" role="contentinfo">
  <div class="pro-footer__wrap">

    {{-- Column: Brand & Contact --}}
    <section class="fcol fcol--brand">
      <a href="{{ url('/') }}" class="fbrand">
        <img src="{{ asset('assets/images/fse-logo.png') }}" alt="AL-FARAN School of Excellence logo" class="fbrand__logo-square">
        <div class="fbrand__text">
          <span class="fbrand__name">AL-FARAN SCHOOL OF EXCELLENCE</span>
          <span class="fbrand__tag">Committed to Excellence</span>
        </div>
      </a>

      <ul class="flist flist--contact">
        <li><a href="mailto:info@alfaran.edu" class="fchip"><iconify-icon icon="solar:mailbox-linear"></iconify-icon> info@alfaran.edu</a></li>
        <li><a href="tel:+92-300-0000000" class="fchip"><iconify-icon icon="solar:phone-outline"></iconify-icon> +92 300 000 0000</a></li>
        <li><a href="https://maps.google.com/?q=Ring+Road,+Peshawar" target="_blank" class="fchip"><iconify-icon icon="solar:map-point-outline"></iconify-icon> Ring Road, Peshawar</a></li>
        <li class="fnote">Mon–Sat: 8:00 AM – 2:00 PM</li>
      </ul>

      {{-- Socials --}}
      <nav class="fsocial">
        <a href="#" aria-label="Facebook" class="fsocial__btn"><iconify-icon icon="mdi:facebook"></iconify-icon></a>
        <a href="#" aria-label="Instagram" class="fsocial__btn"><iconify-icon icon="mdi:instagram"></iconify-icon></a>
        <a href="#" aria-label="Twitter" class="fsocial__btn"><iconify-icon icon="mdi:twitter"></iconify-icon></a>
        <a href="#" aria-label="YouTube" class="fsocial__btn"><iconify-icon icon="mdi:youtube"></iconify-icon></a>
        <a href="#" aria-label="LinkedIn" class="fsocial__btn"><iconify-icon icon="mdi:linkedin"></iconify-icon></a>
      </nav>
    </section>

    {{-- Column: Links --}}
    <section class="fcol">
      <ul class="flist">
        <li><a href="{{ url('/') }}">Home</a></li>
        <li><a href="{{ url('/about') }}">About Us</a></li>
        <li><a href="{{ url('/admissions') }}">Admissions</a></li>
        <li><a href="{{ url('/academics') }}">Academics</a></li>
        <li><a href="{{ url('/news') }}">News & Events</a></li>
        <li><a href="{{ url('/calendar') }}">Academic Calendar</a></li>
        <li><a href="{{ url('/downloads') }}">Downloads</a></li>
        <li><a href="{{ url('/careers') }}">Careers</a></li>
        <li><a href="{{ url('/contact') }}">Contact</a></li>
      </ul>
    </section>

    {{-- Column: Newsletter --}}
    <section class="fcol">
      <p class="ftext">Subscribe to receive updates & notices:</p>
      <form action="{{ url('/subscribe') }}" method="POST" class="fnews">
        @csrf
        <input type="email" name="email" placeholder="Enter your email" required class="finput">
        <button type="submit" class="fbtn">Subscribe</button>
      </form>
    </section>

  </div>

  {{-- Bottom strip --}}
  <div class="pro-footer__bottom">
    <p class="copy">© {{ date('Y') }} AL-FARAN School of Excellence. All rights reserved.</p>
    <ul class="fmini">
      <li><a href="{{ url('/privacy') }}">Privacy</a></li>
      <li><a href="{{ url('/terms') }}">Terms</a></li>
      <li><a href="{{ url('/contact') }}">Support</a></li>
    </ul>
  </div>
</footer>

<style>
  .pro-footer{border-top:1px solid var(--stroke);background:rgba(255,255,255,.8);backdrop-filter:blur(8px);margin-top:30px;}
  .pro-footer__wrap{max-width:1200px;margin:0 auto;padding:28px 14px;display:grid;gap:26px;grid-template-columns:1.4fr 1fr 1.2fr;}

  .fcol{display:flex;flex-direction:column;gap:12px;}
  .ftext{color:var(--muted);font-weight:600;}

  .fbrand{display:flex;align-items:center;gap:12px;text-decoration:none;}
  .fbrand__logo-square{width:56px;height:56px;object-fit:cover;border-radius:12px;box-shadow:0 10px 24px rgba(2,6,23,.1);border:1px solid var(--stroke);}
  .fbrand__name{display:block;font-weight:900;color:var(--ink);font-size:18px;}
  .fbrand__tag{display:block;font-size:12px;font-weight:700;background:linear-gradient(90deg,var(--brand1),var(--brand2));-webkit-background-clip:text;background-clip:text;color:transparent;}

  .flist{list-style:none;padding:0;margin:0;display:grid;gap:8px;}
  .flist a{color:var(--ink);text-decoration:none;font-weight:700;}
  .flist a:hover{color:var(--brand1);}

  .fchip{display:inline-flex;align-items:center;gap:8px;padding:6px 10px;border-radius:10px;border:1px solid var(--stroke);background:var(--chip);color:var(--chip-ink);font-weight:700;}
  .fchip:hover{box-shadow:0 0 0 4px var(--ring);}
  .fnote{color:var(--muted);font-weight:600;}

  .fsocial{display:flex;gap:8px;margin-top:6px;flex-wrap:wrap;}
  .fsocial__btn{width:34px;height:34px;border-radius:8px;display:grid;place-items:center;background:linear-gradient(135deg,rgba(106,123,255,.18),rgba(34,211,238,.18));color:var(--ink);border:1px solid var(--stroke);}
  .fsocial__btn:hover{box-shadow:0 0 0 4px var(--ring);}

  .fnews{display:flex;gap:8px;}
  .finput{flex:1;padding:10px;border-radius:12px;border:1px solid var(--stroke);background:var(--card);font-weight:700;}
  .fbtn{padding:10px 14px;border-radius:12px;font-weight:900;background:linear-gradient(90deg,var(--brand1),var(--brand2));color:white;border:none;cursor:pointer;}
  .fbtn:hover{box-shadow:0 6px 16px rgba(2,6,23,.15);}

  .pro-footer__bottom{border-top:1px dashed var(--stroke);display:flex;align-items:center;justify-content:space-between;gap:12px;padding:14px;max-width:1200px;margin:0 auto;}
  .copy{color:var(--muted);font-weight:600;}
  .fmini{list-style:none;display:flex;gap:14px;margin:0;padding:0;}
  .fmini a{color:var(--muted);text-decoration:none;font-weight:600;}
  .fmini a:hover{color:var(--brand1);}

  @media(max-width:980px){.pro-footer__wrap{grid-template-columns:1fr 1fr;}}
  @media(max-width:720px){.pro-footer__wrap{grid-template-columns:1fr;gap:20px;padding:20px 12px;}.fnews{flex-direction:column;}.pro-footer__bottom{flex-direction:column;align-items:flex-start;gap:8px;}}
</style>
