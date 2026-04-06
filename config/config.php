<?php
session_start();
define('BASE_URL', 'http://localhost/tecmed/');
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'construccion_db');

// Controlador y acción por defecto
define('DEFAULT_CONTROLLER', 'DashboardController');
define('DEFAULT_ACTION', 'index');

// Habilitar errores en desarrollo
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>