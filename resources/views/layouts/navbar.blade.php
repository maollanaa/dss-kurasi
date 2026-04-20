<nav class="navbar navbar-expand-lg navbar-light sticky-top py-2 px-0 mt-4">
    <button class="navbar-toggler ml-3 d-md-none collapsed" type="button" data-toggle="collapse"
        data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="w-100 px-4 d-none d-md-block">
        <h3 class="mb-0">@yield('title', 'Dashboard')</h3>
    </div>

    <ul class="navbar-nav px-3 ml-auto">
        @auth
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-nowrap d-flex align-items-center" href="#" id="navbarDropdown"
                    role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="d-flex flex-column text-right mr-2">
                        <span class="font-weight-bold" style="line-height: 1;">{{ auth()->user()->name }}</span>
                        <small style="font-size: 0.75rem;">{{ auth()->user()->email }}</small>
                    </div>
                    <i data-lucide="user-circle" class="ml-2"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="#">
                        <i data-lucide="settings"></i> Pengaturan
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#logoutModal">
                        <i data-lucide="log-out" class="text-danger"></i> Logout
                    </a>
                </div>
            </li>
        @endauth
    </ul>
</nav>

@push('modal')
    @include('modal.logout')
@endpush