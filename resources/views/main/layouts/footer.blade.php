{{-- Clean Professional Footer (FSE themed, refined structure + typography) --}}
<footer class="pro-footer" role="contentinfo">
  <div class="pro-footer__topline"></div>

  <div class="pro-footer__wrap">

    {{-- Column: Brand & Contact --}}
    <section class="fcol fcol--brand">
      <a href="{{ url('/') }}" class="fbrand" aria-label="AL-FARAN School of Excellence - home">
        <img src="{{ asset('assets/images/fse-logo.png') }}" alt="AL-FARAN School of Excellence logo" class="fbrand__logo-square">
        <div class="fbrand__text">
          <span class="fbrand__name">AL-FARAN SCHOOL OF EXCELLENCE</span>
          <span class="fbrand__tag">Committed to Excellence</span>
        </div>
      </a>

      <ul class="flist flist--contact" aria-label="Contact">
        <li>
          <a href="mailto:alfaranschool20@gmail.com" class="fchip">
            <iconify-icon icon="solar:mailbox-linear" aria-hidden="true"></iconify-icon>
            alfaranschool20@gmail.com
          </a>
        </li>
        <li>
          <a href="tel:+923040507255" class="fchip">
            <iconify-icon icon="solar:phone-outline" aria-hidden="true"></iconify-icon>
            0304 050 7255
          </a>
        </li>
        <li>
          <a href="tel:+923366507032" class="fchip">
            <iconify-icon icon="solar:phone-outline" aria-hidden="true"></iconify-icon>
            0336 650 7032
          </a>
        </li>
        <li>
          <a href="https://maps.google.com/?q=Ring+Road,+Peshawar" target="_blank" class="fchip" rel="noopener">
            <iconify-icon icon="solar:map-point-outline" aria-hidden="true"></iconify-icon>
            Ring Road, Peshawar
          </a>
        </li>
        <li class="fnote">
          <iconify-icon icon="solar:clock-circle-linear" aria-hidden="true"></iconify-icon>
          Mon–Sat: 8:00 AM – 2:00 PM
        </li>
      </ul>

      {{-- Socials --}}
      <nav class="fsocial" aria-label="Social">
        <a href="#" aria-label="Facebook" class="fsocial__btn"><iconify-icon icon="mdi:facebook"></iconify-icon></a>
        <a href="#" aria-label="Instagram" class="fsocial__btn"><iconify-icon icon="mdi:instagram"></iconify-icon></a>
        <a href="#" aria-label="Twitter" class="fsocial__btn"><iconify-icon icon="mdi:twitter"></iconify-icon></a>
        <a href="#" aria-label="YouTube" class="fsocial__btn"><iconify-icon icon="mdi:youtube"></iconify-icon></a>
        <a href="#" aria-label="LinkedIn" class="fsocial__btn"><iconify-icon icon="mdi:linkedin"></iconify-icon></a>
      </nav>
    </section>

    {{-- Column: Quick Links (trimmed & tidy) --}}
    <section class="fcol">
      <h3 class="fhead">Quick Links</h3>
      <ul class="flist flist--links">
        <li><a href="{{ url('/') }}"><span>Home</span></a></li>
        <li><a href="{{ url('/about') }}"><span>About Us</span></a></li>
        <li><a href="{{ url('/admissions') }}"><span>Admissions</span></a></li>
        <li><a href="{{ url('/contact') }}"><span>Contact</span></a></li>
      </ul>
    </section>

    {{-- Column: Helpline (compact info card) --}}
    <section class="fcol">
      <h3 class="fhead">Helpline & Info</h3>

      <div class="fcard">
        <div class="frow">
          <div class="fstat">
            <iconify-icon icon="solar:call-chat-linear"></iconify-icon>
            <div>
              <div class="fstat__label">Admissions Helpline</div>
              <a href="tel:+923040507255" class="fstat__value">0304 050 7255</a>
            </div>
          </div>
          <div class="fstat">
            <iconify-icon icon="solar:phone-calling-linear"></iconify-icon>
            <div>
              <div class="fstat__label">Office</div>
              <a href="tel:+923366507032" class="fstat__value">0336 650 7032</a>
            </div>
          </div>
        </div>

        <div class="frow">
          <div class="fstat">
            <iconify-icon icon="solar:mailbox-linear"></iconify-icon>
            <div>
              <div class="fstat__label">Email</div>
              <a href="mailto:alfaranschool20@gmail.com" class="fstat__value">alfaranschool20@gmail.com</a>
            </div>
          </div>
          <div class="fstat">
            <iconify-icon icon="solar:map-point-linear"></iconify-icon>
            <div>
              <div class="fstat__label">Location</div>
              <a href="https://maps.google.com/?q=Ring+Road,+Peshawar" target="_blank" class="fstat__value" rel="noopener">Ring Road, Peshawar</a>
            </div>
          </div>
        </div>
      </div>
    </section>

  </div>

  {{-- Bottom strip --}}
  <div class="pro-footer__bottom">
    <p class="copy">© {{ date('Y') }} AL-FARAN School of Excellence. All rights reserved.</p>
    <ul class="fmini" aria-label="Policies">
      <li><a href="{{ url('/privacy') }}">Privacy</a></li>
      <li><a href="{{ url('/terms') }}">Terms</a></li>
      <li><a href="{{ url('/contact') }}">Support</a></li>
    </ul>
  </div>
</footer>

<style>
  /* ======= Theme (matches header) ======= */
  :root{ --fse-red:#d82323; --fse-blue:#1f64c8; --fse-sky:#22c1f1; --fse-gold:#e7b308; }

  /* ======= Shell ======= */
  .pro-footer{
    border-top:1px solid var(--stroke);
    background:linear-gradient(180deg, rgba(255,255,255,.95), rgba(255,255,255,.88));
    backdrop-filter:blur(8px);
    margin-top:34px;
  }
  .pro-footer__topline{ height:4px; background:linear-gradient(90deg, var(--fse-red), var(--fse-blue), var(--fse-sky)); }
  .pro-footer__wrap{
    max-width:1200px; margin:0 auto; padding:28px 14px;
    display:grid; gap:28px; grid-template-columns:1.4fr 0.9fr 1.2fr;
  }

  /* ======= Brand & contact ======= */
  .fcol{ display:flex; flex-direction:column; gap:14px; }
  .fhead{ margin:0; font-size:16px; letter-spacing:.25px; color:var(--ink); font-weight:900; }
  .fbrand{ display:flex; align-items:center; gap:12px; text-decoration:none; }
  .fbrand__logo-square{
    width:54px; height:54px; object-fit:cover; border-radius:12px; background:#fff;
    border:1px solid var(--stroke); box-shadow:0 10px 24px rgba(2,6,23,.08);
  }
  .fbrand__name{ display:block; font-weight:900; color:var(--ink); font-size:17px; line-height:1.15; }
  .fbrand__tag{
    display:inline-block; font-size:12px; font-weight:800; line-height:1.1;
    background:linear-gradient(90deg,var(--fse-blue),var(--fse-sky));
    -webkit-background-clip:text; background-clip:text; color:transparent;
  }

  .flist{ list-style:none; padding:0; margin:0; display:grid; gap:10px; }
  .flist a{ color:var(--ink); text-decoration:none; font-weight:700; }
  .flist a:hover{ color:var(--fse-blue); }

  .flist--contact .fchip{
    display:inline-flex; align-items:center; gap:8px; padding:9px 12px;
    border-radius:12px; border:1px solid var(--stroke);
    background:linear-gradient(135deg, rgba(31,100,200,.07), rgba(34,193,241,.07));
    color:var(--ink); font-weight:800;
  }
  .fchip:hover{ box-shadow:0 0 0 4px rgba(31,100,200,.18); }
  .fnote{ color:var(--muted); font-weight:700; display:flex; align-items:center; gap:8px; }

  /* ======= Quick Links (tidy) ======= */
  .flist--links li a{
    display:flex; align-items:center; gap:10px; padding:8px 10px;
    border-radius:10px; border:1px solid transparent;
  }
  .flist--links li a span{ font-weight:800; }
  .flist--links li a:hover{
    border-color:rgba(31,100,200,.22);
    background:rgba(34,193,241,.06);
    box-shadow:0 0 0 3px rgba(31,100,200,.12) inset;
  }

  /* ======= Helpline Card ======= */
  .fcard{
    border:1px solid var(--stroke); border-radius:14px; background:var(--card);
    padding:14px; box-shadow:0 10px 24px rgba(2,6,23,.06);
  }
  .frow{ display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:10px; }
  .fstat{
    display:flex; gap:10px; align-items:flex-start; padding:12px;
    border-radius:12px; background:linear-gradient(180deg, rgba(255,255,255,.94), rgba(255,255,255,.88));
    border:1px solid var(--stroke);
  }
  .fstat iconify-icon{ font-size:20px; color:var(--fse-blue); }
  .fstat__label{ font-size:12px; color:var(--muted); font-weight:800; line-height:1; }
  .fstat__value{ display:block; font-weight:900; color:var(--ink); text-decoration:none; margin-top:4px; }
  .fstat__value:hover{ color:var(--fse-blue); }

  /* ======= Bottom strip ======= */
  .pro-footer__bottom{
    border-top:1px dashed var(--stroke);
    display:flex; align-items:center; justify-content:space-between; gap:12px;
    padding:14px; max-width:1200px; margin:0 auto;
  }
  .copy{ color:var(--muted); font-weight:600; }
  .fmini{ list-style:none; display:flex; gap:14px; margin:0; padding:0; }
  .fmini a{ color:var(--muted); text-decoration:none; font-weight:700; }
  .fmini a:hover{ color:var(--fse-blue); }

  /* ======= Responsive ======= */
  @media(max-width:1060px){ .pro-footer__wrap{ grid-template-columns:1.3fr .8fr 1.1fr; } }
  @media(max-width:980px){ .pro-footer__wrap{ grid-template-columns:1fr 1fr; } }
  @media(max-width:720px){
    .pro-footer__wrap{ grid-template-columns:1fr; gap:20px; padding:20px 12px; }
    .frow{ grid-template-columns:1fr; }
    .pro-footer__bottom{ flex-direction:column; align-items:flex-start; gap:8px; }
  }
</style>
