<?php

// Asegurar que $controller, $method y $params estén definidos
$controller = $controller ?? 'Home';
$method = $method ?? 'index';
$params = $params ?? [];

$controllerName = ucfirst($controller);
$controllerFile = __DIR__ . "/../../Controllers/{$controllerName}.php";

// Si existe el controlador
if (file_exists($controllerFile)) {
    require_once $controllerFile;

    if (class_exists($controllerName)) {
        $instance = new $controllerName();

        if (method_exists($instance, $method)) {
            call_user_func_array([$instance, $method], is_array($params) ? [$params] : []);
        } else {
            require_once __DIR__ . "/../../Controllers/Error.php";
        }
    } else {
        require_once __DIR__ . "/../../Controllers/Error.php";
    }
} else {
    require_once __DIR__ . "/../../Controllers/Error.php";
}
