<aside class="sidebar" id="adminSidebar">

    <div class="sidebar-brand">
        <div class="d-flex align-items-center gap-3">
            <div class="sidebar-logo">
                <img
                    src="{{ asset('images/logos/logo-bwi.png') }}"
                    alt="Logo Kabupaten Banyuwangi"
                    class="sidebar-logo-image"
                >
            </div>

            <div>
                <div class="sidebar-title">
                    E-Voting
                </div>

                <div class="sidebar-subtitle">
                    Desa Barurejo
                </div>
            </div>
        </div>
    </div>

    <nav class="sidebar-menu">

        <a
            href="{{ route('dashboard') }}"
            class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
        >
            <i class="bi bi-grid-1x2-fill"></i>
            <span>Dashboard</span>
        </a>

        <a
            href="{{ route('candidates.index') }}"
            class="nav-link {{ request()->routeIs('candidates.*') ? 'active' : '' }}"
        >
            <i class="bi bi-person-badge-fill"></i>
            <span>Data Kandidat</span>
        </a>

        <a
            href="{{ route('voters.index') }}"
            class="nav-link {{ request()->routeIs('voters.*') ? 'active' : '' }}"
        >
            <i class="bi bi-people-fill"></i>
            <span>Data DPT</span>
        </a>

        <a
            href="{{ route('verification.index') }}"
            class="nav-link {{ request()->routeIs('verification.*') ? 'active' : '' }}"
        >
            <i class="bi bi-person-check-fill"></i>
            <span>Verifikasi Pemilih</span>
        </a>

        <a
            href="{{ route('scan.index') }}"
            class="nav-link {{ request()->routeIs('scan.*') ? 'active' : '' }}"
        >
            <i class="bi bi-qr-code-scan"></i>
            <span>Scan QR</span>
        </a>
            
        <a
            href="{{ route('results.index') }}"
            class="nav-link {{ request()->routeIs('results.*') ? 'active' : '' }}"
        >
            <i class="bi bi-bar-chart-fill"></i>
            <span>Rekapitulasi</span>
        </a>

        <hr class="border-light opacity-25 my-3">

        <a
            href="{{ route('settings.edit') }}"
            class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}"
        >
            <i class="bi bi-gear-fill"></i>
            <span>Pengaturan</span>
        </a>

    </nav>
</aside>