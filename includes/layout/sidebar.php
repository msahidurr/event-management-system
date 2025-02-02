<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="<?= BASE_URL ?>/index.php" class="brand-link">
            <!--begin::Brand Image-->
            <img
                src="<?= BASE_URL ?>/assets/adminlte/img/AdminLTELogo.png"
                alt="AdminLTE Logo"
                class="brand-image opacity-75 shadow" />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light"><?= APP_NAME ?></span>
            <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand-->
    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul
                class="nav sidebar-menu flex-column"
                data-lte-toggle="treeview"
                role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>/dashboard.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link <?= in_array(basename($_SERVER['PHP_SELF']), ['list.php', 'create.php', 'edit.php']) ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>
                            Event
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="display: <?= in_array(basename($_SERVER['PHP_SELF']), ['list.php', 'create.php', 'edit.php']) ? 'block' : 'none' ?>;">
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>/events/list.php" class="nav-link <?= in_array(basename($_SERVER['PHP_SELF']), ['list.php', 'create.php', 'edit.php']) ? 'active' : '' ?>">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Manage Events</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'download.php') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>
                            Report
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="display: <?= (basename($_SERVER['PHP_SELF']) == 'download.php') ? 'block' : 'none' ?>;">
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>/reports/download.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'download.php') ? 'active' : '' ?>">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Event Report</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>