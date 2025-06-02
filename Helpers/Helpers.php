<?php

// BASE DEL SISTEMA

function base_url(): string {
    return BASE_URL;
}

function media(): string {
    return BASE_URL . "Assets";
}


// VISTAS Y COMPONENTES

function getFile(string $url, array $data = []): string {
    extract($data);
    ob_start();
    require "Views/{$url}.php";
    return ob_get_clean();
}

function getModal(string $nameModal, array $data = []): void {
    extract($data);
    require "Views/Template/Modals/{$nameModal}.php";
}

function headerAdmin(array $data = []): void {
    extract($data);
    require_once "Views/Template/header_admin.php";
}

function footerAdmin(array $data = []): void {
    extract($data);
    require_once "Views/Template/footer_admin.php";
}

function headerTienda(array $data = []): void {
    extract($data);
    require_once "Views/Template/header_tienda.php";
}

function footerTienda(array $data = []): void {
    extract($data);
    require_once "Views/Template/footer_tienda.php";
}

// SESIÓN Y SEGURIDAD

function requireLogin(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION['login'])) {
        header('Location: ' . BASE_URL . 'login');
        exit;
    }
}

function getPermisos(int $idmodulo): void {
    require_once "Models/PermisosModel.php";
    $objPermisos = new PermisosModel();
    $idrol = $_SESSION['userData']['idrol'] ?? 0;
    $arrPermisos = $objPermisos->permisosModulo($idrol);

    $_SESSION['permisos'] = $arrPermisos;
    $_SESSION['permisosMod'] = $arrPermisos[$idmodulo] ?? [];
}

function sessionUser(int $idpersona): array {
    require_once "Models/LoginModel.php";
    $objLogin = new LoginModel();
    return $objLogin->sessionLogin($idpersona);
}


// LIMPIEZA DE STRINGS

function strClean(string $cadena): string {
    $cadena = preg_replace(['/\s+/', '/^\s|\s$/'], [' ', ''], $cadena);
    $cadena = trim($cadena);
    $cadena = stripslashes($cadena);

    $peligros = [
        "<script>", "</script>", "SELECT * FROM", "DELETE FROM", "INSERT INTO",
        "DROP TABLE", "OR '1'='1", "LIKE '", "--", "^", "[", "]", "=="
    ];

    return str_ireplace($peligros, "", $cadena);
}

function clear_cadena(string $cadena): string {
    $mapa = [
        'Á'=>'A','À'=>'A','Â'=>'A','Ä'=>'A','á'=>'a','à'=>'a','ä'=>'a','â'=>'a',
        'É'=>'E','È'=>'E','Ê'=>'E','Ë'=>'E','é'=>'e','è'=>'e','ë'=>'e','ê'=>'e',
        'Í'=>'I','Ì'=>'I','Ï'=>'I','Î'=>'I','í'=>'i','ì'=>'i','ï'=>'i','î'=>'i',
        'Ó'=>'O','Ò'=>'O','Ö'=>'O','Ô'=>'O','ó'=>'o','ò'=>'o','ö'=>'o','ô'=>'o',
        'Ú'=>'U','Ù'=>'U','Û'=>'U','Ü'=>'U','ú'=>'u','ù'=>'u','ü'=>'u','û'=>'u',
        'Ñ'=>'N','ñ'=>'n','Ç'=>'C','ç'=>'c',','=>'', '.'=>'', ';'=>'', ':'=>''
    ];
    return strtr($cadena, $mapa);
}

// UTILIDADES DE SEGURIDAD

function passGenerator(int $length = 10): string {
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
    return substr(str_shuffle(str_repeat($chars, $length)), 0, $length);
}

function token(): string {
    return implode('-', array_map(fn() => bin2hex(random_bytes(10)), range(1, 4)));
}

// DINERO Y FORMATO

function formatMoney(float $cantidad): string {
    return number_format($cantidad, 2, SPD, SPM);
}

// DEBUG


function dep(mixed $data): void {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

// CORREO

function sendEmail(array $data, string $template): bool {
    $asunto = $data['asunto'];
    $emailDestino = $data['email'];
    $empresa = NOMBRE_REMITENTE;
    $remitente = EMAIL_REMITENTE;

    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: {$empresa} <{$remitente}>\r\n";

    ob_start();
    require "Views/Template/Email/{$template}.php";
    $mensaje = ob_get_clean();

    return mail($emailDestino, $asunto, $mensaje, $headers);
}
