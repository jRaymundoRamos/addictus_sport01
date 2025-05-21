<?php

class Views
{
    public function getView(object $controller, string $view, array $data = []): void
    {
        $controllerName = get_class($controller);
        $viewPath = ($controllerName === 'Home')
            ? __DIR__ . "/../../Views/{$view}.php"
            : __DIR__ . "/../../Views/{$controllerName}/{$view}.php";

        if (file_exists($viewPath)) {
            // Extrae las variables del array $data como $nombre => valor
            extract($data, EXTR_SKIP);
            require_once $viewPath;
        } else {
            // Mostrar mensaje de error si la vista no existe
            http_response_code(500);
            echo "Vista no encontrada: <code>{$viewPath}</code>";
        }
    }
}
