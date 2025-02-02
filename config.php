<?php

// Automatically detect base URL
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$folder = str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);
$base_url = rtrim("$protocol://$host$folder", '/');

define('APP_NAME', 'Event Management');
define('APP_PATH', __DIR__);
define('BASE_URL', $base_url);
define('DB_HOST', 'localhost');
define('DB_USER', '');
define('DB_PASS', '');
define('DB_NAME', '');
define('APP_INSTALL', false);
