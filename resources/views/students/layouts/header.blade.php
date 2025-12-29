<div class="navbar-header">
  @php
      use Illuminate\Support\Facades\Storage;
      use Illuminate\Support\Str;

      $user      = auth()->user();
      $userName  = trim($user->name ?? 'User');
      $userLabel = strtoupper($userName);
      $initial   = mb_strtoupper(mb_substr($userName, 0, 1));

      // ✅ Find student record for this logged-in user (try both keys)
      $student = \App\Models\Student::where('student_id', $user->id)->first();
      if (!$student) {
          $student = \App\Models\Student::where('user_id', $user->id)->first();
      }

      // ✅ Normalize any stored path to a correct public URL
      $headerPhotoUrl = null;
      if ($student && !empty($student->profile_image_path)) {
          $p = trim((string) $student->profile_image_path);

          // If it's already a full URL
          if (Str::startsWith($p, ['http://', 'https://', '//'])) {
              $headerPhotoUrl = $p;
          } else {
              // Remove leading slashes
              $p = ltrim($p, '/');

              // If someone stored: "storage/profiles/xxx.jpg" => make it "profiles/xxx.jpg"
              if (Str::startsWith($p, 'storage/')) {
                  $p = Str::after($p, 'storage/');
              }

              // If someone stored: "public/profiles/xxx.jpg" => make it "profiles/xxx.jpg"
              if (Str::startsWith($p, 'public/')) {
                  $p = Str::after($p, 'public/');
              }

              // Now build: /storage/profiles/xxx.jpg
              $headerPhotoUrl = Storage::disk('public')->url($p);

              // Cache-buster to avoid browser showing old/broken cached image
              $headerPhotoUrl .= (str_contains($headerPhotoUrl, '?') ? '&' : '?') . 'v=' . time();
          }
      }
  @endphp

  <style>
      .student-topbar-pill{
          display:inline-flex;
          align-items:center;
          gap:.6rem;
          padding:6px 14px 6px 8px;
          border-radius:999px;
          background:linear-gradient(135deg,#f3f4ff,#e0f2fe);
          border:1px solid #d0d7ff;
          box-shadow:0 8px 20px rgba(15,23,42,.12);
          transition:transform .15s ease, box-shadow .15s ease, background .15s ease;
      }
      .student-topbar-pill:hover{
          transform:translateY(-1px);
          box-shadow:0 10px 26px rgba(15,23,42,.16);
          background:linear-gradient(135deg,#e0e7ff,#bfdbfe);
      }

      /* ✅ Avatar always shows initial first */
      .student-topbar-avatar{
          width:32px;
          height:32px;
          border-radius:999px;
          background:#111827;
          color:#f9fafb;
          display:flex;
          align-items:center;
          justify-content:center;
          font-size:14px;
          font-weight:800;
          box-shadow:0 0 0 2px #e5e7eb;
          overflow:hidden;
          flex:0 0 auto;
          position:relative;
      }
      .student-topbar-avatar .initial{
          position:absolute;
          inset:0;
          display:flex;
          align-items:center;
          justify-content:center;
          z-index:1;
      }
      .student-topbar-avatar img{
          position:absolute;
          inset:0;
          width:100%;
          height:100%;
          object-fit:cover;
          display:none;          /* hidden until loaded */
          z-index:2;
      }

      .student-topbar-name{
          font-size:14px;
          font-weight:700;
          letter-spacing:.04em;
          text-transform:uppercase;
          color:#111827;
          white-space:nowrap;
          max-width:190px;
          overflow:hidden;
          text-overflow:ellipsis;
      }
      .student-topbar-role{
          font-size:11px;
          font-weight:500;
          text-transform:uppercase;
          letter-spacing:.12em;
          color:#6b7280;
          margin-top:-2px;
      }
      .student-topbar-chevron{
          font-size:16px;
          color:#6b7280;
      }

      @media (max-width:768px){
          .student-topbar-name{ max-width:120px; font-size:13px; }
          .student-topbar-pill{ padding:6px 10px 6px 6px; }
      }
  </style>

  <div class="row align-items-center justify-content-between">
    {{-- Left side toggles --}}
    <div class="col-auto">
      <div class="d-flex flex-wrap align-items-center gap-4">
        <button type="button" class="sidebar-toggle">
          <iconify-icon icon="heroicons:bars-3-solid" class="icon text-2xl non-active"></iconify-icon>
          <iconify-icon icon="iconoir:arrow-right" class="icon text-2xl active"></iconify-icon>
        </button>
        <button type="button" class="sidebar-mobile-toggle">
          <iconify-icon icon="heroicons:bars-3-solid" class="icon"></iconify-icon>
        </button>
      </div>
    </div>

    {{-- Right side controls --}}
    <div class="col-auto">
      <div class="d-flex flex-wrap align-items-center gap-3">

        {{-- 🔔 Notifications (UNCHANGED) --}}
        <div class="dropdown">
          <button
            id="notifBtn"
            class="position-relative has-indicator w-40-px h-40-px rounded-circle d-flex justify-content-center align-items-center
                   {{ ($headerUnreadCount ?? 0) > 0 ? 'bg-danger text-white' : 'bg-neutral-200' }}"
            type="button" data-bs-toggle="dropdown" aria-expanded="false">

            <iconify-icon icon="iconoir:bell" class="text-xl"></iconify-icon>

            <span id="notifBadge"
                  class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger {{ ($headerUnreadCount ?? 0) > 0 ? '' : 'd-none' }}"
                  style="font-size:.65rem;">
              {{ $headerUnreadCount ?? 0 }}
            </span>
          </button>

          <div id="notifDropdown" class="dropdown-menu to-top dropdown-menu-lg p-0">
            <div class="m-16 py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
              <h6 class="text-lg text-primary-light fw-semibold mb-0">Notifications</h6>
              <span id="notifHeaderCount"
                    class="text-primary-600 fw-semibold text-lg w-40-px h-40-px rounded-circle bg-base d-flex justify-content-center align-items-center">
                {{ $headerUnreadCount ?? 0 }}
              </span>
            </div>

            <div class="max-h-400-px overflow-y-auto scroll-sm pe-4">
              @forelse(($headerNotifications ?? collect()) as $n)
                <a href="{{ route('student.notifications.index') }}"
                   class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between {{ $loop->odd ? 'bg-neutral-50' : '' }}">
                  <div class="text-black d-flex align-items-center gap-3">
                    <span class="w-44-px h-44-px bg-info-subtle text-info-main rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                      <iconify-icon icon="mdi:bullhorn-outline" class="icon text-xxl"></iconify-icon>
                    </span>
                    <div>
                      <h6 class="text-md fw-semibold mb-4">{{ \Illuminate\Support\Str::limit($n->title, 40) }}</h6>
                      <p class="mb-0 text-sm text-secondary-light text-w-200-px">
                        {{ \Illuminate\Support\Str::limit(strip_tags($n->body), 80) }}
                      </p>
                    </div>
                  </div>
                  <span class="text-sm text-secondary-light flex-shrink-0">
                    {{ $n->published_at?->diffForHumans() }}
                  </span>
                </a>
              @empty
                <div class="px-24 py-12 text-center text-muted">No notifications</div>
              @endforelse
            </div>

            <div class="text-center py-12 px-16">
              <a href="{{ route('student.notifications.index') }}" class="text-primary-600 fw-semibold text-md">
                See All Notifications
              </a>
            </div>
          </div>
        </div>
        {{-- /Notifications --}}

        {{-- ✅ Profile pill with photo --}}
        <div class="dropdown">
          <button
              class="student-topbar-pill border-0 bg-transparent"
              type="button"
              id="studentProfileDropdown"
              data-bs-toggle="dropdown"
              aria-expanded="false">

              <span class="student-topbar-avatar">
                  <span class="initial">{{ $initial }}</span>

                  @if($headerPhotoUrl)
                    <img
                      id="topbarStudentImg"
                      src="{{ $headerPhotoUrl }}"
                      alt="Profile Photo">
                  @endif
              </span>

              <div class="d-flex flex-column align-items-start">
                  <span class="student-topbar-name">{{ $userLabel }}</span>
                  <span class="student-topbar-role">Student Portal</span>
              </div>

              <span class="student-topbar-chevron ms-1">▾</span>
          </button>

          <div class="dropdown-menu dropdown-menu-end shadow-sm border-0 py-1"
               aria-labelledby="studentProfileDropdown"
               style="min-width: 180px; border-radius: 14px;">

            <button type="button" class="dropdown-item text-muted small" disabled>
              Signed in as <strong>{{ $userName }}</strong>
            </button>
            <div class="dropdown-divider my-1"></div>

            <a href="{{ route('logout') }}"
               class="dropdown-item d-flex align-items-center gap-2 text-danger fw-semibold"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              Logout
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
              @csrf
            </form>
          </div>
        </div>
        {{-- /Profile --}}
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const img = document.getElementById('topbarStudentImg');
  if (img) {
    img.onload = () => { img.style.display = 'block'; };
    img.onerror = () => { img.remove(); }; // initial stays visible
    if (img.complete && img.naturalWidth > 0) img.onload();
  }

  // Notifications (UNCHANGED)
  const btn   = document.getElementById('notifBtn');
  const badge = document.getElementById('notifBadge');
  const headerCount = document.getElementById('notifHeaderCount');

  if (btn) {
    btn.addEventListener('shown.bs.dropdown', function () {
      btn.classList.remove('bg-danger','text-white');
      btn.classList.add('bg-neutral-200');
      if (badge) { badge.classList.add('d-none'); badge.textContent = '0'; }
      if (headerCount) headerCount.textContent = '0';

      fetch("{{ route('student.notifications.markLatestRead') }}", {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json'
        }
      })
      .then(r => r.json())
      .then(data => {
        const remaining = Number(data?.remaining ?? 0);
        if (remaining > 0) {
          btn.classList.add('bg-danger','text-white');
          badge.classList.remove('d-none');
          badge.textContent = remaining;
          if (headerCount) headerCount.textContent = remaining;
        }
      })
      .catch(() => {});
    });
  }
});
</script>
