<?php
require_once 'config.php';

if(APP_INSTALL == true) {
    header('Location: ' . BASE_URL . '/index.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $app_name = trim($_POST["app_name"]);
    $db_host = trim($_POST["db_host"]);
    $db_user = trim($_POST["db_user"]);
    $db_pass = trim($_POST["db_pass"]);
    $db_name = trim($_POST["db_name"]);

    // Automatically detect base URL
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $folder = str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);
    $base_url = rtrim("$protocol://$host$folder", '/');

    if (!empty($app_name) && !empty($base_url) && !empty($db_host) && !empty($db_user) && !empty($db_name)) {
        $error = '';
        // Connect to MySQL
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

        // Check connection
        if ($conn->connect_error) {
            $error = "Connection failed: " . $conn->connect_error;
        }

        $sql_file = APP_PATH . "/event_management.sql";

        // Read the SQL file
        $sql = file_get_contents($sql_file);
        if ($sql === false) {
            $error = "Error reading SQL file.";
        }

        // Execute SQL queries
        if ($conn->multi_query($sql)) {
            do {
                // Clear results if needed
            } while ($conn->next_result());
        } else {
            $error =  "Error importing SQL file: " . $conn->error;
        }

        // Close connection
        $conn->close();

        if(empty($error)) {
            // Define the config file path
            $config_file = APP_PATH . "/config.php";

            @chmod($config_file, 0777);

            // Create the new config content
            $config_content = <<<PHP
                <?php

                define('APP_NAME', '$app_name');
                define('APP_PATH', __DIR__);
                define('BASE_URL', '$base_url');
                define('DB_HOST', '$db_host');
                define('DB_USER', '$db_user');
                define('DB_PASS', '$db_pass');
                define('DB_NAME', '$db_name');
                define('APP_INSTALL', true);

                PHP;

            if (@file_put_contents($config_file, $config_content)) {
                header("Location:" . BASE_URL . "/index.php");
                exit();
            } else {
                $error = "Error writing to file. Please check permissions.";
            }     
        }
        
    } else {
        $error = "All fields are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation</title>
    <!--end::Primary Meta Tags-->
    <!--begin::Fonts-->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
        crossorigin="anonymous" />
    <!--end::Fonts-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
        integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg="
        crossorigin="anonymous" />
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
        crossorigin="anonymous" />
    <!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/adminlte/css/adminlte.css" />
    <!--end::Required Plugin(AdminLTE)-->
    <!-- apexcharts -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
        integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
        crossorigin="anonymous" />
    <!-- jsvectormap -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
        integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4="
        crossorigin="anonymous" />

    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.min.css">
</head>

<body>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 offset-md-3" style="margin-top: 20px;">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                <form action="" method="post" class="needs-validation" novalidate="">
                    <div class="card card-info">
                        <div class="card-header text-center">
                            <h3 class="card-title">Installation</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="app_name">App Name</label>
                                <input type="text" name="app_name" class="form-control" required="true" value="<?= isset($_POST['app_name']) ? $_POST['app_name'] : '' ?>">
                            </div>

                            <div class="form-group">
                                <label for="db_host">Database Host</label>
                                <input type="text" name="db_host" class="form-control" value="<?= isset($_POST['db_host']) ? $_POST['db_host'] : 'localhost' ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="database_name">Database Name</label>
                                <input type="text" name="db_name" class="form-control" required value="<?= isset($_POST['db_name']) ? $_POST['db_name'] : '' ?>">
                            </div>

                            <div class="form-group">
                                <label for="db_user">Database User</label>
                                <input type="text" name="db_user" class="form-control" required value="<?= isset($_POST['db_user']) ? $_POST['db_user'] : '' ?>">
                            </div>

                            <div class="form-group">
                                <label for="db_pass">Database Password</label>
                                <input type="text" name="db_pass" class="form-control" >
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            <button type="submit" class="btn btn-primary">Setup</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script
        src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
        integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
        crossorigin="anonymous"></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
        src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>

    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.min.js"></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="<?= BASE_URL ?>/assets/adminlte/js/adminlte.js"></script>
    <script>
        (() => {
            'use strict';

            const forms = document.querySelectorAll('.needs-validation');

            Array.from(forms).forEach((form) => {
                form.addEventListener(
                    'submit',
                    (event) => {
                        console.log('s')
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        }

                        form.classList.add('was-validated');
                    },
                    false,
                );
            });
        })();
    </script>
</body>

</html>