<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Admin')</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <style>
        :root {
            --primary: #176b4d;
            --primary-dark: #0f5137;
            --accent: #d4a72c;
            --background: #f4f7f6;
            --sidebar-width: 260px;
        }

        body {
            min-height: 100vh;
            margin: 0;
            background: var(--background);
            color: #26332e;
        }

        .admin-wrapper {
            min-height: 100vh;
        }

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: var(--sidebar-width);
            background: linear-gradient(
                180deg,
                var(--primary-dark),
                var(--primary)
            );
            color: white;
            overflow-y: auto;
            z-index: 1040;
        }

        .sidebar-brand {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        }
  
        .sidebar-logo{
            width:60px;
            height:60px;
            background:white;
            border-radius:16px;
            display:flex;
            justify-content:center;
            align-items:center;
            padding:6px;
        }

        .sidebar-logo-image{
            width:100%;
            height:100%;
            object-fit:contain;
        }

        .sidebar-title {
            font-size: 16px;
            font-weight: 700;
            line-height: 1.2;
        }

        .sidebar-subtitle {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.72);
        }

        .sidebar-menu {
            padding: 20px 14px;
        }

        .sidebar-menu .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255, 255, 255, 0.78);
            border-radius: 12px;
            padding: 12px 14px;
            margin-bottom: 6px;
            font-weight: 500;
        }

        .sidebar-menu .nav-link:hover,
        .sidebar-menu .nav-link.active {
            color: white;
            background: rgba(255, 255, 255, 0.15);
        }

        .sidebar-menu .nav-link i {
            width: 22px;
            font-size: 18px;
        }

        .main-content {
            min-height: 100vh;
            margin-left: var(--sidebar-width);
        }

        .topbar {
            min-height: 76px;
            background: white;
            border-bottom: 1px solid #e4e9e6;
            padding: 0 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .content-area {
            padding: 32px;
        }

        .page-heading {
            font-weight: 750;
            margin-bottom: 4px;
        }

        .card {
            border-radius: 16px;
        }

        .btn-primary {
            --bs-btn-bg: var(--primary);
            --bs-btn-border-color: var(--primary);
            --bs-btn-hover-bg: var(--primary-dark);
            --bs-btn-hover-border-color: var(--primary-dark);
        }

        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.25s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .content-area {
                padding: 22px 16px;
            }

            .topbar {
                padding: 0 16px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
<div class="admin-wrapper">

    @include('partials.sidebar')

    <main class="main-content">
        @include('partials.navbar')

        <section class="content-area">
            @yield('content')
        </section>
    </main>

</div>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

<script>
    const sidebarButton = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('adminSidebar');

    if (sidebarButton && sidebar) {
        sidebarButton.addEventListener('click', function () {
            sidebar.classList.toggle('show');
        });
    }
</script>

@stack('scripts')
</body>
</html>