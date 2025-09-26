{{-- Clean, Balanced Footer (2 columns, email single-line) --}}
<footer class="pro-footer" role="contentinfo">
  <div class="pro-footer__topline"></div>

  <div class="pro-footer__wrap">
    <!-- Left: Brand / identity -->
    <section class="fcol fcol--brand">
      <a href="{{ url('/') }}" class="fbrand" aria-label="AL-FARAN School of Excellence - home">
        <img src="{{ asset('assets/images/fse-logo.png') }}" alt="AL-FARAN School of Excellence logo" class="fbrand__logo-square">
        <div class="fbrand__text">
          <span class="fbrand__eyebrow">AL-FARAN</span>
          <span class="fbrand__name">School of Excellence</span>
          <span class="fbrand__tag">Committed to Excellence</span>
        </div>
      </a>

      <p class="flead">Shaping confident learners with strong values and skills.</p>

      <!-- Socials -->
      <nav class="fsocial" aria-label="Social">
        <a href="#" aria-label="Facebook" class="fsocial__btn"><iconify-icon icon="mdi:facebook"></iconify-icon></a>
        <a href="#" aria-label="Instagram" class="fsocial__btn"><iconify-icon icon="mdi:instagram"></iconify-icon></a>
        <a href="#" aria-label="Twitter" class="fsocial__btn"><iconify-icon icon="mdi:twitter"></iconify-icon></a>
        <a href="#" aria-label="YouTube" class="fsocial__btn"><iconify-icon icon="mdi:youtube"></iconify-icon></a>
        <a href="#" aria-label="LinkedIn" class="fsocial__btn"><iconify-icon icon="mdi:linkedin"></iconify-icon></a>
      </nav>
    </section>

    <!-- Right: Contact card (single source of truth) -->
    <section class="fcol fcol--contact">
      <div class="fpanel" aria-label="Contact details">
        <h3 class="fhead">Contact</h3>

        <div class="fcontact-grid">
          <!-- EMAIL: single-line + full width -->
          <a href="mailto:alfaranschool20@gmail.com"
             class="fbox fbox--wide fbox--singleline"
             aria-label="Email"
             title="alfaranschool20@gmail.com">
            <div class="fbox__icon"><iconify-icon icon="solar:mailbox-linear"></iconify-icon></div>
            <div class="fbox__body">
              <span class="fbox__label">Email</span>
              <span class="fbox__value">alfaranschool20@gmail.com</span>
            </div>
          </a>

          <a href="https://maps.google.com/?q=Awan+Kalan+Pakistani+Pull+Hujra+Shah+Muqeem" target="_blank" rel="noopener" class="fbox" aria-label="Location">
            <div class="fbox__icon"><iconify-icon icon="solar:map-point-linear"></iconify-icon></div>
            <div class="fbox__body">
              <span class="fbox__label">Location</span>
              <span class="fbox__value">Awan Kalan Pakistani Pull Hujra Shah Muqeem</span>
            </div>
          </a>

          <a href="tel:+923040507255" class="fbox" aria-label="Admissions phone">
            <div class="fbox__icon"><iconify-icon icon="solar:phone-outline"></iconify-icon></div>
            <div class="fbox__body">
              <span class="fbox__label">Phone (Admissions)</span>
              <span class="fbox__value">0304 050 7255</span>
            </div>
          </a>

          <a href="tel:+923366507032" class="fbox" aria-label="Office phone">
            <div class="fbox__icon"><iconify-icon icon="solar:phone-outline"></iconify-icon></div>
            <div class="fbox__body">
              <span class="fbox__label">Phone (Office)</span>
              <span class="fbox__value">0336 650 7032</span>
            </div>
          </a>
        </div>
      </div>
    </section>
  </div>

  <!-- Bottom strip -->
  <div class="pro-footer__bottom">
    <p class="copy">© {{ date('Y') }} AL-FARAN School of Excellence. All rights reserved.</p>
  </div>
</footer>

<style>
  /* ===== Theme ===== */
  :root{
    --fse-red:#d82323; --fse-blue:#1f64c8; --fse-sky:#22c1f1;
    --ink:#0f172a; --muted:#64748b; --stroke:#e5e7eb; --card:#ffffff;
    --soft:#f8fafc;
  }

  /* ===== Shell ===== */
  .pro-footer{
    border-top:1px solid var(--stroke);
    background:
      radial-gradient(1200px 400px at 80% 20%, rgba(34,193,241,.08), rgba(255,255,255,0) 60%),
      linear-gradient(180deg, rgba(255,255,255,.97), rgba(255,255,255,.92));
    backdrop-filter:blur(8px);
    margin-top:36px;
  }
  .pro-footer__topline{ height:4px; background:linear-gradient(90deg, var(--fse-red), var(--fse-blue), var(--fse-sky)); }
  .pro-footer__wrap{
    max-width:1200px; margin:0 auto; padding:28px 18px 20px;
    display:grid; gap:24px; grid-template-columns:1.1fr 1fr;
  }

  /* ===== Brand side ===== */
  .fcol{ display:flex; flex-direction:column; gap:14px; }
  .fbrand{ display:flex; align-items:center; gap:14px; text-decoration:none; }
  .fbrand__logo-square{
    width:60px; height:60px; object-fit:cover; border-radius:14px; background:#fff;
    border:1px solid var(--stroke); box-shadow:0 10px 24px rgba(2,6,23,.08);
  }
  .fbrand__text{ display:flex; flex-direction:column; line-height:1.1; }
  .fbrand__eyebrow{
    font-size:11px; letter-spacing:.18em; text-transform:uppercase;
    color:var(--muted); font-weight:800;
  }
  .fbrand__name{ font-weight:900; color:var(--ink); font-size:20px; }
  .fbrand__tag{
    font-size:12px; font-weight:800;
    background:linear-gradient(90deg,var(--fse-blue),var(--fse-sky));
    -webkit-background-clip:text; background-clip:text; color:transparent;
  }
  .flead{ margin:4px 0 0; color:var(--muted); font-weight:600; }

  /* ===== Socials ===== */
  .fsocial{ display:flex; gap:12px; margin-top:6px; }
  .fsocial__btn{
    width:36px; height:36px; display:grid; place-items:center;
    border-radius:10px; border:1px solid var(--stroke);
    color:var(--muted); background:#fff; box-shadow:0 6px 18px rgba(2,6,23,.04);
  }
  .fsocial__btn:hover{ color:var(--fse-blue); box-shadow:0 0 0 4px rgba(31,100,200,.14); }

  /* ===== Contact panel ===== */
  .fpanel{
    border:1px solid var(--stroke); border-radius:16px; background:var(--card);
    padding:16px; box-shadow:0 14px 34px rgba(2,6,23,.06), inset 0 1px 0 #fff;
    position:relative; overflow:hidden;
  }
  .fpanel::after{
    content:""; position:absolute; inset:auto -40% -40% auto; width:300px; height:300px;
    background:radial-gradient(closest-side, rgba(31,100,200,.08), rgba(31,100,200,0));
  }
  .fhead{ margin:0 0 10px; font-size:16px; letter-spacing:.25px; color:var(--ink); font-weight:900; }

  .fcontact-grid{
    display:grid; grid-template-columns:1fr 1fr; gap:12px;
  }
  .fbox{
    display:flex; align-items:flex-start; gap:10px; text-decoration:none;
    padding:12px; border-radius:12px; border:1px solid var(--stroke);
    background:linear-gradient(180deg, #fff, #fbfdff);
    box-shadow:0 8px 22px rgba(2,6,23,.05);
    color:var(--ink);
  }
  .fbox:hover{ box-shadow:0 0 0 4px rgba(31,100,200,.1); }
  .fbox__icon iconify-icon{ font-size:20px; color:var(--fse-blue); }
  .fbox__label{ font-size:12px; color:var(--muted); font-weight:800; line-height:1; }
  .fbox__value{ display:block; font-weight:700; margin-top:4px; word-break:normal; } /* default wrap */

  /* Email single-line */
  .fbox--wide{ grid-column:1 / -1; } /* takes full row */
  .fbox--singleline .fbox__value{
    white-space:nowrap;           /* keep on one line */
    overflow:hidden;              /* clip overflow */
    text-overflow:ellipsis;       /* add ellipsis if too long */
  }

  /* ===== Bottom strip ===== */
  .pro-footer__bottom{
    border-top:1px dashed var(--stroke);
    display:flex; align-items:center; justify-content:center;
    padding:14px 18px; max-width:1200px; margin:0 auto;
  }
  .copy{ color:var(--muted); font-weight:600; font-size:14px; }

  /* ===== Responsive ===== */
  @media (max-width:980px){
    .pro-footer__wrap{ grid-template-columns:1fr; }
    .fpanel{ order:2; }
  }
  @media (max-width:640px){
    .fcontact-grid{ grid-template-columns:1fr; }
    .fbrand__name{ font-size:18px; }
  }
</style>
