<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>

    @php
        $isDash   = request()->routeIs('student.dashboard');
        $isHW     = request()->routeIs('student.homeworks');
        $isExam   = request()->routeIs('student.exams');
        $isMR     = request()->routeIs('student.monthlyreports');
        $isRes    = request()->routeIs('student.results');
        $isLeave  = request()->routeIs('student.vacationrequests*');

        $openAcademic = $isHW || $isExam;
        $openReports  = $isMR || $isRes;
        $openLeave    = $isLeave;
    @endphp

    <div>
        <a href="{{ route('student.dashboard') }}" class="sidebar-logo">
           
        </a>
    </div>

    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">

            {{-- Dashboard --}}
            <li class="dropdown {{ $isDash ? 'active open' : '' }}">
                <a href="javascript:void(0)">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>
                <ul class="sidebar-submenu" style="{{ $isDash ? 'display:block' : '' }}">
                    <li class="{{ $isDash ? 'active' : '' }}">
                        <a href="{{ route('student.dashboard') }}">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Overview
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Academic --}}
            <li class="sidebar-menu-group-title">Academic</li>
            <li class="dropdown {{ $openAcademic ? 'active open' : '' }}">
                <a href="javascript:void(0)">
                    <iconify-icon icon="mdi:book-education-outline" class="menu-icon"></iconify-icon>
                    <span>Schedules</span>
                </a>
                <ul class="sidebar-submenu" style="{{ $openAcademic ? 'display:block' : '' }}">
                    <li class="{{ $isHW ? 'active' : '' }}">
                        <a href="{{ route('student.homeworks') }}">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Homework Schedule
                        </a>
                    </li>
                    <li class="{{ $isExam ? 'active' : '' }}">
                        <a href="{{ route('student.exams') }}">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Exam Schedule
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Reports & Results --}}
            <li class="sidebar-menu-group-title">Reports</li>
            <li class="dropdown {{ $openReports ? 'active open' : '' }}">
                <a href="javascript:void(0)">
                    <iconify-icon icon="mdi:calendar-month-outline" class="menu-icon"></iconify-icon>
                    <span>Progress</span>
                </a>
                <ul class="sidebar-submenu" style="{{ $openReports ? 'display:block' : '' }}">
                    <li class="{{ $isMR ? 'active' : '' }}">
                        <a href="{{ route('student.monthlyreports') }}">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Monthly Reports
                        </a>
                    </li>
                    <li class="{{ $isRes ? 'active' : '' }}">
                        <a href="{{ route('student.results') }}">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Results
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Vacation / Leave Requests --}}
          <li class="sidebar-menu-group-title">Requests</li>
<li class="dropdown {{ $openLeave ? 'active open' : '' }}">
    <a href="javascript:void(0)">
        <iconify-icon icon="mdi:calendar-check-outline" class="menu-icon"></iconify-icon>
        <span>Leave Requests</span>
    </a>
    <ul class="sidebar-submenu" style="{{ $openLeave ? 'display:block' : '' }}">
        <li class="{{ $isLeave ? 'active' : '' }}">
            <a href="{{ route('student.vacation-requests.index') }}">
                <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> My Requests
            </a>
        </li>
        <li>
            <a href="{{ route('student.vacation-requests.create') }}">
                <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> New Request
            </a>
        </li>
    </ul>
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
