<!--begin::Header-->
<nav class="app-header navbar navbar-expand bg-body">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Start Navbar Links-->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="bi bi-list"></i>
                </a>
            </li>
        </ul>
        <!--end::Start Navbar Links-->
        <!--begin::End Navbar Links-->
        <ul class="navbar-nav ms-auto">
            <!--end::Fullscreen Toggle-->
            <!--begin::User Menu Dropdown-->
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <img
                        src="<?= BASE_URL ?>/assets/adminlte/img/user2-160x160.jpg"
                        class="user-image rounded-circle shadow"
                        alt="User Image" />
                    <span class="d-none d-md-inline"><?= $_SESSION['username'] ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <!--begin::Menu Footer-->
                    <li class="user-footer">
                        <a href="<?= BASE_URL ?>/logout.php" class="btn btn-default">Sign out</a>
                    </li>
                    <!--end::Menu Footer-->
                </ul>
            </li>
            <!--end::User Menu Dropdown-->
        </ul>
        <!--end::End Navbar Links-->
    </div>
    <!--end::Container-->
</nav>
<!--end::Header-->