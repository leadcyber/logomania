<aside class="app-sidebar sticky" id="sidebar">

    <!-- Start::main-sidebar-header -->
    <div class="main-sidebar-header">
        <a href="{{ url('index') }}" class="header-logo">
            <img src="{{ asset('build/assets/images/brand-logos/desktop-logo.png') }}" alt="logo" class="desktop-logo">
            <img src="{{ asset('build/assets/images/brand-logos/toggle-logo.png') }}" alt="logo" class="toggle-logo">
            <img src="{{ asset('build/assets/images/brand-logos/desktop-dark.png') }}" alt="logo"
                class="desktop-dark">
            <img src="{{ asset('build/assets/images/brand-logos/toggle-dark.png') }}" alt="logo"
                class="toggle-dark">
            <img src="{{ asset('build/assets/images/brand-logos/desktop-white.png') }}" alt="logo"
                class="desktop-white">
            <img src="{{ asset('build/assets/images/brand-logos/toggle-white.png') }}" alt="logo"
                class="toggle-white">
        </a>
    </div>
    <!-- End::main-sidebar-header -->

    <!-- Start::main-sidebar -->
    <div class="main-sidebar" id="sidebar-scroll">

        <!-- Start::nav -->
        <nav class="main-menu-container nav nav-pills flex-column sub-open">
            <div class="slide-left" id="slide-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24"
                    viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
                </svg>
            </div>
            <ul class="main-menu">
                <!-- Start::slide__category -->
                <li class="slide__category"><span class="category-name">Main</span></li>
                <!-- End::slide__category -->

                <!-- Start::slide -->
                <li class="slide">
                    <a href="javascript:void(0);;" class="side-menu__item">
                        <i class="bx bx-home side-menu__icon"></i>
                        <span class="side-menu__label">Dashboard</span>
                    </a>
                </li>
                <li class="slide">
                    <a href="javascript:void(0);;" class="side-menu__item">
                        <i class="bx bx-medal side-menu__icon"></i>
                        <span class="side-menu__label">Logo</span>
                    </a>
                </li>
                <li class="slide">
                    <a href="javascript:void(0);;" class="side-menu__item">
                        <i class="bx bx-book side-menu__icon"></i>
                        <span class="side-menu__label">Transaction</span>
                    </a>
                </li>

                
                <!-- Start::slide__category -->
                <li class="slide__category"><span class="category-name">Admin</span></li>
                <!-- End::slide__category -->
                
                <li class="slide">
                    <a href="javascript:void(0);;" class="side-menu__item">
                        <i class="bx bx-edit side-menu__icon"></i>
                        <span class="side-menu__label">Localization</span>
                    </a>
                </li>
                <li class="slide">
                    <a href="javascript:void(0);;" class="side-menu__item">
                        <i class="bx bx-user side-menu__icon"></i>
                        <span class="side-menu__label">User</span>
                    </a>
                </li>
                <li class="slide">
                    <a href="javascript:void(0);;" class="side-menu__item">
                        <i class="bx bx-dollar-circle side-menu__icon"></i>
                        <span class="side-menu__label">Subscription</span>
                    </a>
                </li>
                <li class="slide">
                    <a href="javascript:void(0);;" class="side-menu__item">
                        <i class="bx bx-history side-menu__icon"></i>
                        <span class="side-menu__label">Log</span>
                    </a>
                </li>

                <!-- Start::slide__category -->
                <li class="slide__category"><span class="category-name">Logo Generation</span></li>
                <!-- End::slide__category -->

                <li class="slide">
                    <a href="javascript:void(0);;" class="side-menu__item">
                        <i class="bx bx-font side-menu__icon"></i>
                        <span class="side-menu__label">Font</span>
                    </a>
                </li>
                <li class="slide">
                    <a href="javascript:void(0);;" class="side-menu__item">
                        <i class="bx bx-buildings side-menu__icon"></i>
                        <span class="side-menu__label">Family</span>
                    </a>
                </li>
                <li class="slide">
                    <a href="javascript:void(0);;" class="side-menu__item">
                        <i class="bx bx-image side-menu__icon"></i>
                        <span class="side-menu__label">Icon</span>
                    </a>
                </li>
                <li class="slide">
                    <a href="javascript:void(0);;" class="side-menu__item">
                        <i class="bx bx-palette side-menu__icon"></i>
                        <span class="side-menu__label">Palette</span>
                    </a>
                </li>

                <li class="slide has-sub">
                    <a href="javascript:void(0);;" class="side-menu__item">
                        <i class="bx bx-layer side-menu__icon"></i>
                        <span class="side-menu__label">Nested Menu</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide side-menu__label1">
                            <a href="javascript:void(0);">Nested Menu</a>
                        </li>
                        <li class="slide">
                            <a href="javascript:void(0);;" class="side-menu__item">Nested-1</a>
                        </li>
                        <li class="slide has-sub">
                            <a href="javascript:void(0);;" class="side-menu__item">Nested-2
                                <i class="fe fe-chevron-right side-menu__angle"></i></a>
                            <ul class="slide-menu child2">
                                <li class="slide">
                                    <a href="javascript:void(0);;" class="side-menu__item">Nested-2-1</a>
                                </li>
                                <li class="slide has-sub">
                                    <a href="javascript:void(0);;" class="side-menu__item">Nested-2-2
                                        <i class="fe fe-chevron-right side-menu__angle"></i></a>
                                    <ul class="slide-menu child3">
                                        <li class="slide">
                                            <a href="javascript:void(0);;" class="side-menu__item">Nested-2-2-1</a>
                                        </li>
                                        <li class="slide">
                                            <a href="javascript:void(0);;" class="side-menu__item">Nested-2-2-2</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <!-- End::slide -->

            </ul>
            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                    width="24" height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
                </svg></div>
        </nav>
        <!-- End::nav -->

    </div>
    <!-- End::main-sidebar -->

</aside>
