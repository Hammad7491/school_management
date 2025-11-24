<div class="navbar-header">
    <div class="row align-items-center justify-content-between">
        
        <!-- Left: Sidebar toggles (unchanged) -->
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

        <!-- Right: ONLY Profile Icon -->
        <div class="col-auto">
            <div class="d-flex flex-wrap align-items-center gap-3">

                <!-- USER ICON BUTTON -->
                <div class="dropdown">
                    <button 
                        class="w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center"
                        type="button" data-bs-toggle="dropdown">

                        <!-- Clean User Icon -->
                        <iconify-icon icon="mdi:account-circle" class="text-primary-light text-3xl"></iconify-icon>

                    </button>

                    <!-- DROPDOWN -->
                    <div class="dropdown-menu to-top dropdown-menu-sm">

                        <!-- User Header -->
                        <div
                            class="py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                            
                            <div>
                                <!-- Logged-in User Name -->
                                <h6 class="text-lg text-primary-light fw-semibold mb-2">
                                    {{ Auth::user()->name ?? 'User' }}
                                </h6>
                                <span class="text-secondary-light fw-medium text-sm">
                                    Logged In
                                </span>
                            </div>

                            <button type="button" class="hover-text-danger">
                                <iconify-icon icon="radix-icons:cross-1" class="icon text-xl"></iconify-icon>
                            </button>
                        </div>

                        <!-- Options -->
                        <ul class="to-top-list">

                            <!-- LOGOUT -->
                            <li>
                                <a 
                                    href="{{ route('logout') }}"
                                    class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-danger d-flex align-items-center gap-3"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                >
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
