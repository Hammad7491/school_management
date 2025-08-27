<div class="navbar-header">
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
        {{-- Dark mode --}}
        <button type="button" data-theme-toggle
          class="w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center"></button>

        {{-- Language (keep yours) --}}
        <div class="dropdown d-none d-sm-inline-block">
          <button class="has-indicator w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center"
                  type="button" data-bs-toggle="dropdown">
            <img src="{{ asset('assets/images/lang-flag.png') }}" alt="lang"
                 class="w-24 h-24 object-fit-cover rounded-circle">
          </button>
          <div class="dropdown-menu to-top dropdown-menu-sm">
            <div class="py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
              <h6 class="text-lg text-primary-light fw-semibold mb-0">Choose Your Language</h6>
            </div>
            <div class="max-h-400-px overflow-y-auto scroll-sm pe-8">
              {{-- your language options --}}
            </div>
          </div>
        </div>

        {{-- Messages (keep yours) --}}
        <div class="dropdown">
          <button class="has-indicator w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center"
                  type="button" data-bs-toggle="dropdown">
            <iconify-icon icon="mage:email" class="text-primary-light text-xl"></iconify-icon>
          </button>
          <div class="dropdown-menu to-top dropdown-menu-lg p-0">
            <div class="m-16 py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
              <h6 class="text-lg text-primary-light fw-semibold mb-0">Message</h6>
              <span class="text-primary-600 fw-semibold text-lg w-40-px h-40-px rounded-circle bg-base d-flex justify-content-center align-items-center">05</span>
            </div>
            <div class="max-h-400-px overflow-y-auto scroll-sm pe-4">
              {{-- message items --}}
            </div>
            <div class="text-center py-12 px-16">
              <a href="javascript:void(0)" class="text-primary-600 fw-semibold text-md">See All Message</a>
            </div>
          </div>
        </div>

        {{-- 🔔 Notifications --}}
        <div class="dropdown">
          <button
            id="notifBtn"
            class="position-relative has-indicator w-40-px h-40-px rounded-circle d-flex justify-content-center align-items-center
                   {{ ($headerUnreadCount ?? 0) > 0 ? 'bg-danger text-white' : 'bg-neutral-200' }}"
            type="button" data-bs-toggle="dropdown" aria-expanded="false">

            <iconify-icon icon="iconoir:bell" class="text-xl"></iconify-icon>

            {{-- OUTSIDE badge --}}
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

        {{-- Profile --}}
        <div class="dropdown">
          <button class="d-flex justify-content-center align-items-center rounded-circle" type="button" data-bs-toggle="dropdown">
            <img src="{{ asset('assets/images/user.png') }}" alt="user" class="w-40-px h-40-px object-fit-cover rounded-circle">
          </button>
          <div class="dropdown-menu to-top dropdown-menu-sm">
            <div class="py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
              <div>
                <h6 class="text-lg text-primary-light fw-semibold mb-2">{{ auth()->user()->name ?? 'User' }}</h6>
                <span class="text-secondary-light fw-medium text-sm">Student</span>
              </div>
              <button type="button" class="hover-text-danger">
                <iconify-icon icon="radix-icons:cross-1" class="icon text-xl"></iconify-icon>
              </button>
            </div>
            <ul class="to-top-list">
              <li><a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-primary d-flex align-items-center gap-3" href="#">
                <iconify-icon icon="solar:user-linear" class="icon text-xl"></iconify-icon> My Profile</a>
              </li>
              <li><a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-primary d-flex align-items-center gap-3" href="#">
                <iconify-icon icon="tabler:message-check" class="icon text-xl"></iconify-icon> Inbox</a>
              </li>
              <li><a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-primary d-flex align-items-center gap-3" href="#">
                <iconify-icon icon="icon-park-outline:setting-two" class="icon text-xl"></iconify-icon> Setting</a>
              </li>
              <li>
                <a href="{{ route('logout') }}"
                   class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-danger d-flex align-items-center gap-3"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                  <iconify-icon icon="lucide:power" class="icon text-xl"></iconify-icon> Log Out
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                  @csrf
                </form>
              </li>
            </ul>
          </div>
        </div>
        {{-- /Profile --}}
      </div>
    </div>
  </div>
</div>

{{-- Mark latest notifications as read when dropdown opens --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
  const btn   = document.getElementById('notifBtn');
  const badge = document.getElementById('notifBadge');
  const headerCount = document.getElementById('notifHeaderCount');

  btn.addEventListener('shown.bs.dropdown', function () {
    // Optimistic UI clear
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
        // still have unread (beyond top 5) – restore badge + color
        btn.classList.add('bg-danger','text-white');
        badge.classList.remove('d-none');
        badge.textContent = remaining;
        if (headerCount) headerCount.textContent = remaining;
      }
    })
    .catch(() => { /* ignore errors for now */ });
  });
});
</script>
