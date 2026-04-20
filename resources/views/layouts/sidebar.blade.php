<nav class="sidebar col-md-3 col-lg-2 d-md-block border-right collapse min-vh-100" id="sidebarMenu">
    <div class="sidebar-sticky">
        <div class="text-center py-5">
            <h5 class="font-weight-bold mb-0 text-primary">Kurasi UMKM</h5>
        </div>
        <ul class="nav flex-column px-3">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('dashboard*') || request()->is('/') ? 'active' : '' }}"
                    href="{{ url('/dashboard') }}">
                    <i class="bi bi-house fa-fw"></i>
                    Dashboard
                </a>
            </li>

            @if(auth()->check() && auth()->user()->role === 'admin')
                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                    <span>Manajemen Sistem</span>
                </h6>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/kriteria*') ? 'active' : '' }}"
                        href="{{ route('admin.kriteria') }}">
                        <i class="bi bi-list-ul fa-fw"></i>
                        Kriteria & Parameter
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/bobot*') ? 'active' : '' }}" href="{{ url('/admin/bobot') }}">
                        <i class="bi bi-scales fa-fw"></i>
                        Bobot Kriteria
                    </a>
                </li>

                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                    <span>Manajemen Kurasi</span>
                </h6>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/periode*') ? 'active' : '' }}"
                        href="{{ url('/admin/periode') }}">
                        <i class="bi bi-calendar fa-fw"></i>
                        Periode Kurasi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/produk*') ? 'active' : '' }}"
                        href="{{ url('/admin/produk') }}">
                        <i class="bi bi-box fa-fw"></i>
                        Data Produk
                    </a>
                </li>
            @endif

            @if(auth()->check() && auth()->user()->role === 'kurator')
                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                    <span>Menu Kurator</span>
                </h6>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('kurator/penilaian*') ? 'active' : '' }}"
                        href="{{ url('/kurator/penilaian') }}">
                        <i class="bi bi-pencil-square fa-fw"></i>
                        Proses Kurasi
                    </a>
                </li>
            @endif

            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                <span>Laporan</span>
            </h6>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('hasil-kurasi*') ? 'active' : '' }}"
                    href="{{ url('/hasil-kurasi') }}">
                    <i class="bi bi-bar-chart fa-fw"></i>
                    Hasil Kurasi
                </a>
            </li>
        </ul>
    </div>
</nav>
