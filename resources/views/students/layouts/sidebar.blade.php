<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>

    <div>
        <a href="{{ url('/') }}" class="sidebar-logo">
            <img src="{{ asset('assets/images/logo.png') }}" alt="site logo" class="light-logo">
            <img src="{{ asset('assets/images/logo-light.png') }}" alt="site logo" class="dark-logo">
            <img src="{{ asset('assets/images/logo-icon.png') }}" alt="site logo" class="logo-icon">
        </a>
    </div>

    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">

            {{-- Dashboard --}}
            <li class="{{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                <a href="{{ route('student.dashboard') }}">
                    <iconify-icon icon="mdi:view-dashboard-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>
            </li>

            {{-- Homework Schedule --}}
            <li class="{{ request()->routeIs('student.homeworks') ? 'active' : '' }}">
                <a href="{{ route('student.homeworks') }}">
                    <iconify-icon icon="mdi:book-edit-outline" class="menu-icon"></iconify-icon>
                    <span>Homework Schedule</span>
                </a>
            </li>

            {{-- Exam Schedule --}}
            <li class="{{ request()->routeIs('student.exams') ? 'active' : '' }}">
                <a href="{{ route('student.exams') }}">
                    <iconify-icon icon="mdi:clipboard-text-outline" class="menu-icon"></iconify-icon>
                    <span>Exam Schedule</span>
                </a>
            </li>

            {{-- Monthly Reports --}}
            <li class="{{ request()->routeIs('student.monthlyreports') ? 'active' : '' }}">
                <a href="{{ route('student.monthlyreports') }}">
                    <iconify-icon icon="mdi:calendar-month-outline" class="menu-icon"></iconify-icon>
                    <span>Monthly Reports</span>
                </a>
            </li>

        </ul>
    </div>

    <div class="p-3 mt-auto">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="btn btn-sm btn-outline-danger w-100" type="submit">
                <iconify-icon icon="mdi:logout"></iconify-icon> Logout
            </button>
        </form>
    </div>
</aside>
