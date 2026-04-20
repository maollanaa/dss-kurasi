<nav class="sidebar col-md-3 col-lg-2 d-md-block border-right collapse min-vh-100" id="sidebarMenu">
    <div class="sidebar-sticky d-flex flex-column">
        <div class="text-center py-4 mt-3 sidebar-brand">
            <h5 class="font-weight-bold mb-0 text-primary">
                <span class="brand-text">Kurasi UMKM</span>
            </h5>
        </div>
        <ul class="nav flex-column mb-4 nav-list">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('dashboard*') || request()->is('/') ? 'active' : '' }}"
                    href="{{ url('/dashboard') }}">
                    <div class="nav-link-inner">
                        <span class="nav-icon"><i data-lucide="home"></i></span>
                        <span class="nav-text">Dashboard</span>
                    </div>
                </a>
            </li>

            @if(auth()->check() && auth()->user()->role === 'admin')
                <h6 class="sidebar-heading mt-4 mb-1 text-muted">
                    <span class="nav-text">Manajemen Sistem</span>
                </h6>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/kriteria*') ? 'active' : '' }}"
                        href="{{ url('/admin/kriteria') }}">
                        <div class="nav-link-inner">
                            <span class="nav-icon"><i data-lucide="list"></i></span>
                            <span class="nav-text">Kriteria & Parameter</span>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/bobot*') ? 'active' : '' }}"
                        href="{{ url('/admin/bobot') }}">
                        <div class="nav-link-inner">
                            <span class="nav-icon"><i data-lucide="scale"></i></span>
                            <span class="nav-text">Bobot Kriteria</span>
                        </div>
                    </a>
                </li>

                <h6 class="sidebar-heading mt-4 mb-1 text-muted">
                    <span class="nav-text">Manajemen Kurasi</span>
                </h6>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/periode*') ? 'active' : '' }}"
                        href="{{ url('/admin/periode') }}">
                        <div class="nav-link-inner">
                            <span class="nav-icon"><i data-lucide="calendar"></i></span>
                            <span class="nav-text">Periode Kurasi</span>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/produk*') ? 'active' : '' }}"
                        href="{{ url('/admin/produk') }}">
                        <div class="nav-link-inner">
                            <span class="nav-icon"><i data-lucide="package"></i></span>
                            <span class="nav-text">Data Produk</span>
                        </div>
                    </a>
                </li>
            @endif

            @if(auth()->check() && auth()->user()->role === 'kurator')
                <h6 class="sidebar-heading mt-4 mb-1 text-muted">
                    <span class="nav-text">Menu Kurator</span>
                </h6>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('kurator/penilaian*') ? 'active' : '' }}"
                        href="{{ url('/kurator/penilaian') }}">
                        <div class="nav-link-inner">
                            <span class="nav-icon"><i data-lucide="square-pen"></i></span>
                            <span class="nav-text">Proses Kurasi</span>
                        </div>
                    </a>
                </li>
            @endif

            <h6 class="sidebar-heading mt-4 mb-1 text-muted">
                <span class="nav-text">Laporan</span>
            </h6>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('hasil-kurasi*') ? 'active' : '' }}"
                    href="{{ url('/hasil-kurasi') }}">
                    <div class="nav-link-inner">
                        <span class="nav-icon"><i data-lucide="bar-chart-3"></i></span>
                        <span class="nav-text">Hasil Kurasi</span>
                    </div>
                </a>
            </li>
        </ul>

        {{-- Minimize Button (desktop lg+ only) --}}
        <div class="sidebar-toggle-wrapper mt-auto mb-4 py-3 d-none d-lg-flex justify-content-center">
            <button
                class="btn sidebar-toggle-btn rounded-circle bg-primary-light d-flex align-items-center justify-content-center shadow-none"
                id="sidebarToggle">
                <span class="btn-icon">
                    <i data-lucide="chevron-left" id="toggleIcon"></i>
                </span>
            </button>
        </div>

        {{-- Close Button (mobile only, dismisses sidebar) --}}
        <div class="sidebar-close-wrapper mt-auto mb-4 py-3 d-flex d-md-none justify-content-center">
            <button
                class="btn sidebar-close-btn rounded-circle bg-primary-light d-flex align-items-center justify-content-center shadow-none"
                data-toggle="collapse" data-target="#sidebarMenu" aria-controls="sidebarMenu">
                <span class="btn-icon">
                    <i data-lucide="x"></i>
                </span>
            </button>
        </div>
    </div>
</nav>