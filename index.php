<?php
require_once 'config/config.php';
require_once 'config/database.php';

spl_autoload_register(function ($class) {
    $paths = ['controllers/', 'models/'];
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
    die("Clase $class no encontrada");
});

$controllerName = isset($_GET['controller']) ? ucfirst($_GET['controller']) . 'Controller' : 'ObraController';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';
$id = isset($_GET['id']) ? $_GET['id'] : null;

if (class_exists($controllerName)) {
    $controller = new $controllerName();
    if (method_exists($controller, $action)) {
        $id ? $controller->$action($id) : $controller->$action();
    } else {
        die("Acción no encontrada: $action");
    }
} else {
    die("Controlador no encontrado: $controllerName");
}
?>