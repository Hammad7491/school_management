<div class="navbar-header">
    @php
        use Illuminate\Support\Str;

        $user    = auth()->user();
        $name    = $user->name  ?? 'User';
        $initial = Str::upper(Str::substr($name, 0, 1));
    @endphp

    <div class="row align-items-center justify-content-between">
        {{-- LEFT: sidebar toggles --}}
        <div class="col-auto">
            <div class="d-flex flex-wrap align-items-center gap-4">
                {{-- Desktop Sidebar Toggle --}}
                <button type="button" class="sidebar-toggle">
                    <iconify-icon icon="heroicons:bars-3-solid" class="icon text-2xl non-active"></iconify-icon>
                    <iconify-icon icon="iconoir:arrow-right" class="icon text-2xl active"></iconify-icon>
                </button>

                {{-- Mobile Sidebar Toggle --}}
                <button type="button" class="sidebar-mobile-toggle">
                    <iconify-icon icon="heroicons:bars-3-solid" class="icon"></iconify-icon>
                </button>
            </div>
        </div>

        {{-- RIGHT: compact user pill ONLY (no notifications) --}}
        <div class="col-auto">
            <div class="d-flex flex-wrap align-items-center gap-3">

                {{-- USER PILL --}}
                <div class="dropdown">
                    <button
                        type="button"
                        data-bs-toggle="dropdown"
                        class="d-flex align-items-center gap-2 px-3 py-2 rounded-pill shadow-sm bg-white border border-light-subtle">

                        {{-- Initial circle --}}
                        <span class="d-inline-flex justify-content-center align-items-center rounded-circle"
                              style="width:32px;height:32px;background:linear-gradient(135deg,#4f46e5,#06b6d4);color:#fff;font-weight:700;">
                            {{ $initial }}
                        </span>

                        {{-- Name --}}
                        <span class="fw-semibold text-dark text-uppercase" style="font-size:13px;">
                            {{ Str::limit($name, 20) }}
                        </span>

                        {{-- Small chevron --}}
                        <iconify-icon icon="mdi:chevron-down" class="text-muted text-sm"></iconify-icon>
                    </button>

                    <div class="dropdown-menu dropdown-menu-end to-top dropdown-menu-sm">
                        <div class="py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                            <div>
                                <h6 class="text-lg text-primary-light fw-semibold mb-2">
                                    {{ $name }}
                                </h6>
                                <span class="text-secondary-light fw-medium text-sm">
                                    Logged in user
                                </span>
                            </div>
                            <button type="button" class="hover-text-danger">
                                <iconify-icon icon="radix-icons:cross-1" class="icon text-xl"></iconify-icon>
                            </button>
                        </div>

                        <ul class="to-top-list">
                            <li>
                                <a href="{{ route('logout') }}"
                                   class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-danger d-flex align-items-center gap-3"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <iconify-icon icon="lucide:power" class="icon text-xl"></iconify-icon>
                                    Log Out
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
