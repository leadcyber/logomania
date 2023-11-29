<header class="app-header">

    <!-- Start::main-header-container -->
    <div class="main-header-container container-fluid">

        <!-- Start::header-content-left -->
        <div class="header-content-left">

            <!-- Start::header-element -->
            <div class="header-element">
                <div class="horizontal-logo">
                    <a href="{{ url('/') }}" class="header-logo">
                        <img src="{{ asset('build/assets/images/brand-logos/toggle-logo.png') }}" alt="logo"
                            class="toggle-logo">
                        <img src="{{ asset('build/assets/images/brand-logos/toggle-dark.png') }}" alt="logo"
                            class="toggle-dark">
                    </a>
                </div>
            </div>
            <!-- End::header-element -->

            <!-- Start::header-element -->
            <div class="header-element">
                <!-- Start::header-link -->
                <a href="javascript:void(0);" class="sidemenu-toggle header-link" data-bs-toggle="sidebar">
                    <span class="open-toggle">
                        <i class="ri-menu-3-line fs-20"></i>
                    </span>
                </a>
                <!-- End::header-link -->
            </div>
            <!-- End::header-element -->

        </div>
        <!-- End::header-content-left -->

        <!-- Start::header-content-right -->
        <div class="header-content-right">

            <!-- Start::header-element -->
            <div class="header-element country-selector mr-2 me-0">
                <!-- Start::header-link|dropdown-toggle -->
                <a href="javascript:void(0);" class="header-link dropdown-toggle" data-bs-auto-close="outside"
                    data-bs-toggle="dropdown">
                    <img src="{{ asset('build/assets/images/flags/'.app()->getLocale().'.jpg') }}" alt="img"
                        class="rounded-circle header-link-icon">
                </a>
                <!-- End::header-link|dropdown-toggle -->
                <ul class="main-header-dropdown dropdown-menu dropdown-menu-end" data-popper-placement="none">
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="?lang=en">
                            <span class="avatar avatar-xs lh-1 me-2">
                                <img src="{{ asset('build/assets/images/flags/en.jpg') }}" alt="img">
                            </span>
                            English
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="?lang=fr">
                            <span class="avatar avatar-xs lh-1 me-2">
                                <img src="{{ asset('build/assets/images/flags/fr.jpg') }}" alt="img">
                            </span>
                            French
                        </a>
                    </li>
                </ul>
            </div>
            <!-- End::header-element -->

            <!-- Start::header-element -->
            <div class="header-element align-items-center">
                <!-- Start::header-link|switcher-icon -->
                <div class="btn-list d-lg-none d-block">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-primary-light">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ url('/register') }}" class="btn btn-primary-light">
                            Sign Up
                        </a>
                    @endauth
                    <button class="btn btn-icon btn-success switcher-icon" data-bs-toggle="offcanvas"
                        data-bs-target="#switcher-canvas">
                        <i class="ri-settings-3-line"></i>
                    </button>
                </div>
                <!-- End::header-link|switcher-icon -->
            </div>
            <!-- End::header-element -->

        </div>
        <!-- End::header-content-right -->

    </div>
    <!-- End::main-header-container -->

</header>
