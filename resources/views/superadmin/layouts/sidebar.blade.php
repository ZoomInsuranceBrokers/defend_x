<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
        <a class="sidebar-brand brand-logo" href="{{ route('superadmin.dashboard') }}"><img
                src="{{ asset('assets/images/img/logo.png') }}" alt="logo"></a>
        <a class="sidebar-brand brand-logo-mini" href="{{ route('superadmin.dashboard') }}"><img
                src="{{ asset('assets/images/img/logo.png') }}" alt="logo"></a>
    </div>
    <ul class="nav">
        <li class="nav-item profile">
            <div class="profile-desc">
                <div class="profile-pic">
                    <div class="count-indicator">
                        <img class="img-xs rounded-circle " src="{{ asset('assets/images/faces/face15.jpg') }}"
                            alt="">
                        <span class="count bg-success"></span>
                    </div>
                    <div class="profile-name">
                        <h5 class="mb-0 font-weight-normal">{{ Auth::user()->full_name }}</h5>
                        <span>Super Admin</span>
                    </div>
                </div>
                <a href="#" id="profile-dropdown" data-bs-toggle="dropdown"><i
                        class="mdi mdi-dots-vertical"></i></a>
                <div class="dropdown-menu dropdown-menu-right sidebar-dropdown preview-list"
                    aria-labelledby="profile-dropdown">
                    <a href="#" class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-dark rounded-circle">
                                <i class="mdi mdi-cog text-primary"></i>
                            </div>
                        </div>
                        <div class="preview-item-content">
                            <p class="preview-subject ellipsis mb-1 text-small">Account settings</p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-dark rounded-circle">
                                <i class="mdi mdi-onepassword  text-info"></i>
                            </div>
                        </div>
                        <div class="preview-item-content">
                            <p class="preview-subject ellipsis mb-1 text-small">Change Password</p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-dark rounded-circle">
                                <i class="mdi mdi-calendar-today text-success"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </li>
        <li class="nav-item nav-category">
            <span class="nav-link">Navigation</span>
        </li>
        <li class="nav-item menu-items {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('superadmin.dashboard') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-speedometer"></i>
                </span>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        <li class="nav-item menu-items">
            <a class="nav-link" href="pages/icons/font-awesome.html">
                <span class="menu-icon">
                    <i class="mdi mdi-security"></i>
                </span>
                <span class="menu-title">Product Master</span>
                <i class="menu-arrow"></i>
            </a>
        </li>

        <li class="nav-item menu-items {{ request()->routeIs('superadmin.company_details') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('superadmin.company_details') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-contacts"></i>
                </span>
                <span class="menu-title">Company Master</span>
            </a>
        </li>

        <li class="nav-item menu-items {{ request()->routeIs('superadmin.user_details') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('superadmin.user_details') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-playlist-play"></i>
                </span>
                <span class="menu-title">User Master</span>
                <i class="menu-arrow"></i>
            </a>
        </li>

        <li class="nav-item menu-items {{ request()->routeIs('superadmin.vendor_details') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('superadmin.vendor_details') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-table-large"></i>
                </span>
                <span class="menu-title">Vendor Master</span>
                <i class="menu-arrow"></i>
            </a>
        </li>

        <li class="nav-item menu-items">
            <a class="nav-link" href="docs/documentation.html">
                <span class="menu-icon">
                    <i class="mdi mdi-file-document"></i>
                </span>
                <span class="menu-title">Reports</span>
            </a>
        </li>
    </ul>
</nav>
