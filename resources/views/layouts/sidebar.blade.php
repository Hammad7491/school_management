<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>

    <div>
        <a href="index.html" class="sidebar-logo">
            <img src="assets/images/logo.png" alt="site logo" class="light-logo">
            <img src="assets/images/logo-light.png" alt="site logo" class="dark-logo">
            <img src="assets/images/logo-icon.png" alt="site logo" class="logo-icon">
        </a>
    </div>

    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">

            {{-- Dashboard --}}
            @can('view dashboard')
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Overview
                        </a>
                    </li>
                </ul>
            </li>
            @endcan

            {{-- Users --}}
            @canany(['view users','create users','view roles','view permissions'])
            <li class="sidebar-menu-group-title">Users</li>
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                    <span>User Management</span>
                </a>
                <ul class="sidebar-submenu">
                    @can('create users')
                    <li>
                        <a href="{{ route('admin.users.create') }}">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Add Users
                        </a>
                    </li>
                    @endcan
                    @can('view users')
                    <li>
                        <a href="{{ route('admin.users.index') }}">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Users List
                        </a>
                    </li>
                    @endcan
                    @can('view roles')
                    <li>
                        <a href="{{ route('admin.roles.index') }}">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Roles & Permissions
                        </a>
                    </li>
                    @endcan
                    @can('view permissions')
                    <li>
                        <a href="{{ route('admin.permissions.index') }}">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Permissions
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcanany

            {{-- Classes --}}
            @canany(['create classes','view classes'])
            <li class="sidebar-menu-group-title">Classes</li>
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="mdi:school-outline" class="menu-icon"></iconify-icon>
                    <span>Class Management</span>
                </a>
                <ul class="sidebar-submenu">
                    @can('create classes')
                    <li>
                        <a href="{{ route('classes.create') }}">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Add Class
                        </a>
                    </li>
                    @endcan
                    @can('view classes')
                    <li>
                        <a href="{{ route('classes.index') }}">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Classes List
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcanany

            {{-- Courses --}}
            @canany(['create courses','view courses'])
            <li class="sidebar-menu-group-title">Courses</li>
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="ph:book-open-text-duotone" class="menu-icon"></iconify-icon>
                    <span>Course Management</span>
                </a>
                <ul class="sidebar-submenu">
                    @can('create courses')
                    <li>
                        <a href="{{ route('courses.create') }}">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Add Course
                        </a>
                    </li>
                    @endcan
                    @can('view courses')
                    <li>
                        <a href="{{ route('courses.index') }}">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Courses List
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcanany

            {{-- Students --}}
            @canany(['create students','view students'])
            <li class="sidebar-menu-group-title">Students</li>
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="mdi:account-school-outline" class="menu-icon"></iconify-icon>
                    <span>Student Management</span>
                </a>
                <ul class="sidebar-submenu">
                    @can('create students')
                    <li>
                        <a href="{{ route('students.create') }}">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Add Student
                        </a>
                    </li>
                    @endcan
                    @can('view students')
                    <li>
                        <a href="{{ route('students.index') }}">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Students List
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcanany

            {{-- Homework --}}
            @canany(['create homeworks','view homeworks'])
            <li class="sidebar-menu-group-title">Homework</li>
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="mdi:book-edit-outline" class="menu-icon"></iconify-icon>
                    <span>Homework Management</span>
                </a>
                <ul class="sidebar-submenu">
                    @can('create homeworks')
                    <li>
                        <a href="{{ route('homeworks.create') }}">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Add Homework
                        </a>
                    </li>
                    @endcan
                    @can('view homeworks')
                    <li>
                        <a href="{{ route('homeworks.index') }}">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Homework List
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcanany

            {{-- Exams --}}
            @canany(['create exams','view exams'])
            <li class="sidebar-menu-group-title">Exams</li>
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="mdi:clipboard-text-outline" class="menu-icon"></iconify-icon>
                    <span>Exam Management</span>
                </a>
                <ul class="sidebar-submenu">
                    @can('create exams')
                    <li>
                        <a href="{{ route('exams.create') }}">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Add Exam
                        </a>
                    </li>
                    @endcan
                    @can('view exams')
                    <li>
                        <a href="{{ route('exams.index') }}">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Exam List
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcanany

            {{-- Email (example static item) --}}
            <li>
                <a href="email.html">
                    <iconify-icon icon="mage:email" class="menu-icon"></iconify-icon>
                    <span>Email</span>
                </a>
            </li>

        </ul>
    </div>
</aside>
