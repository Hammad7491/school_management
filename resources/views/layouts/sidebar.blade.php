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
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="index.html">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> AI
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Users --}}
            <li class="sidebar-menu-group-title">Users</li>

            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                    <span>User Management</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('admin.users.create') }}">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Add Users
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users.index') }}">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Users List
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.roles.index') }}">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Roles & Permissions
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.permissions.index') }}">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Permissions
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Classes --}}
            <li class="sidebar-menu-group-title">Classes</li>

            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="mdi:school-outline" class="menu-icon"></iconify-icon>
                    <span>Class Management</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('classes.create') }}">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Add Class
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('classes.index') }}">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Classes List
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Courses --}}
            <li class="sidebar-menu-group-title">Courses</li>

            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="ph:book-open-text-duotone" class="menu-icon"></iconify-icon>
                    <span>Course Management</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('courses.create') }}">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Add Course
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('courses.index') }}">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Courses List
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Students --}}
            <li class="sidebar-menu-group-title">Students</li>

            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="mdi:account-school-outline" class="menu-icon"></iconify-icon>
                    <span>Student Management</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('students.create') }}">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Add Student
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('students.index') }}">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Students List
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Email --}}
            <li>
                <a href="email.html">
                    <iconify-icon icon="mage:email" class="menu-icon"></iconify-icon>
                    <span>Email</span>
                </a>
            </li>

        </ul>
    </div>
</aside>
