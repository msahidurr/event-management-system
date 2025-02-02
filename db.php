<?php
require_once 'config.php';

if (APP_INSTALL == false) {
    header('Location: ' . BASE_URL . '/install.php');
    exit();
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
