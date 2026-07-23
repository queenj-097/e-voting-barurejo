<header class="topbar">

    <div class="d-flex align-items-center gap-3">
        <button
            type="button"
            class="btn btn-outline-secondary d-lg-none"
            id="sidebarToggle"
        >
            <i class="bi bi-list"></i>
        </button>

        <div>
            <div class="fw-bold">
                Panel Administrator
            </div>

            <small class="text-secondary">
                E-Voting Desa Barurejo
            </small>
        </div>
    </div>

    <div class="dropdown">

        <button
            class="btn border-0 d-flex align-items-center gap-3"
            data-bs-toggle="dropdown"
        >

            <div class="text-end d-none d-sm-block">

                <div class="fw-semibold">
                    {{ auth()->user()->name }}
                </div>

                <small class="text-secondary">
                    {{ ucfirst(auth()->user()->role) }}
                </small>

            </div>

            <div
                class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center"
                style="width:42px;height:42px;"
            >
                <i class="bi bi-person-fill fs-5"></i>
            </div>

        </button>

        <ul class="dropdown-menu dropdown-menu-end shadow">

            <li>
                <h6 class="dropdown-header">
                    {{ auth()->user()->email }}
                </h6>
            </li>

            <li>
                <hr class="dropdown-divider">
            </li>

            <li>

                <form
                    action="{{ route('logout') }}"
                    method="POST"
                >
                    @csrf

                    <button
                        class="dropdown-item text-danger"
                        type="submit"
                    >
                        <i class="bi bi-box-arrow-right me-2"></i>
                        Keluar
                    </button>

                </form>

            </li>

        </ul>

    </div>

</header>