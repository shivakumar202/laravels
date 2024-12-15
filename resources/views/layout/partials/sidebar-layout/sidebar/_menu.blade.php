<!--begin::sidebar menu-->
<div class="app-sidebar-menu overflow-hidden flex-column-fluid">
    <!--begin::Menu wrapper-->
    <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper hover-scroll-overlay-y my-5" data-kt-scroll="true"
        data-kt-scroll-activate="true" data-kt-scroll-height="auto"
        data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
        data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
        <!--begin::Menu-->
        <div class="menu menu-column menu-rounded menu-sub-indention px-3 fw-semibold fs-6" id="#kt_app_sidebar_menu"
            data-kt-menu="true" data-kt-menu-expand="false">
            <!--begin:Menu item-->

            <!--end:Menu link-->
            <!--begin:Menu sub-->
            <!--begin:Menu item-->
            <div class="menu-item">
                <!--begin:Menu link-->
                <a class="menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                    href="{{ route('dashboard') }}">
                    <span class="menu-icon">
                        <i class="bi bi-house "></i>
                    </span>
                    <span class="menu-title">DASHBOARD</span>
                </a>
                <!--end:Menu link-->
            </div>
            <!--end:Menu item-->
            <!--end:Menu sub-->

            <!--begin:Menu item-->
            <div class="menu-item pt-5">
                <!--begin:Menu content-->
                <div class="menu-content">
                    <span class="menu-heading fw-bold text-uppercase fs-7">MATERIALS</span>
                </div>
                <!--end:Menu content-->
            </div>

            <div class="menu-item">
                <!--begin:Menu link-->
                <a class="menu-link {{ request()->routeIs('godown-management.materials.index*') ? 'active' : '' }}"
                    href="{{ route('godown-management.materials.index') }}">
                    <span class="menu-icon">
                        <i class="bi bi-tools"></i>
                    </span>
                    <span class="menu-title">STOCK AT GODOWN</span>
                </a>
                <!--end:Menu link-->
            </div>
            <!--end:Menu item-->
            <div class="menu-item">
                <!--begin:Menu link-->
                <a class="menu-link {{ request()->routeIs('customer-management.customers.index*') ? 'active' : '' }}"
                    href="{{ route('customer-management.customers.index') }}">
                    <span class="menu-icon">
                        <i class="bi bi-person"></i>
                    </span>
                    <span class="menu-title">NEW CUSTOMER</span>
                </a>
                <!--end:Menu link-->
            </div>

            <div class="menu-item">
                <!--begin:Menu link-->
                <a class="menu-link {{ request()->routeIs('rents-management.rent-material.*') ? 'active' : '' }}"
                    href="{{ route('rents-management.rent-material.index') }}">
                    <span class="menu-icon">
                        <i class="bi bi-hammer"></i>
                    </span>
                    <span class="menu-title">RENT MATERIAL</span>
                </a>
                <!--end:Menu link-->
            </div>

            <div class="menu-item">
                <!--begin:Menu link-->
                <a class="menu-link {{ request()->routeIs('rents-management.return-rent-material.*') ? 'active' : '' }}"
                    href="{{ route('rents-management.return-rent-material.index') }}">
                    <span class="menu-icon">
                        <i class="bi bi-back"></i>
                    </span>
                    <span class="menu-title">RETURN RENT MATERIAL</span>
                </a>
                <!--end:Menu link-->
            </div>
            <!--begin:Menu item-->
            <div class="menu-item">
                <!--begin:Menu link-->
                <a class="menu-link {{ request()->routeIs('rents-management.today-return-rent-material.*') ? 'active' : '' }}"
                    href="{{ route('rents-management.today-return-rent-material.getreturns') }}">
                    <span class="menu-icon">
                        <i class="bi bi-back"></i>
                    </span>
                    <span class="menu-title">TODAY RETURNS</span>
                </a>
                <!--end:Menu link-->
            </div>
            <div class="menu-item">
                <!--begin:Menu link-->
                <a class="menu-link {{ request()->routeIs('rents-management.dues') ? 'active' : '' }}"
                    href="{{ route('rents-management.dues') }}">
                    <span class="menu-icon">
                        <i class="bi bi-wallet"></i>
                    </span>
                    <span class="menu-title">PAYMENTS AND PENDINGS</span>
                </a>
                <!--end:Menu link-->
            </div>
            <div class="menu-item pt-5">
                <!--begin:Menu content-->
                <div class="menu-content">
                    <span class="menu-heading fw-bold text-uppercase fs-7">Apps</span>
                </div>
                <!--end:Menu content-->
            </div>
            <!--end:Menu item-->
            <!--begin:Menu item-->
            <div data-kt-menu-trigger="click"
                class="menu-item menu-accordion {{ request()->routeIs('user-management.*') ? 'here show' : '' }}">
                <!--begin:Menu link-->
                <span class="menu-link">
                    <span class="menu-icon">{!! getIcon('abstract-28', 'fs-2') !!}</span>
                    <span class="menu-title">User Management</span>
                    <span class="menu-arrow"></span>
                </span>
                <!--end:Menu link-->
                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-accordion">
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('user-management.users.*') ? 'active' : '' }}"
                            href="{{ route('user-management.users.index') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Users</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                    <!--begin:Menu item-->
                    @can('write user management')
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('user-management.roles.*') ? 'active' : '' }}"
                            href="{{ route('user-management.roles.index') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Roles</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ request()->routeIs('user-management.permissions.*') ? 'active' : '' }}"
                            href="{{ route('user-management.permissions.index') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Permissions</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    @endcan
                    <!--end:Menu item-->
                </div>
                <!--end:Menu sub-->
            </div>
            <!--end:Menu item-->

        </div>
        <!--end::Menu-->
    </div>
    <!--end::Menu wrapper-->
</div>
<!--end::sidebar menu-->