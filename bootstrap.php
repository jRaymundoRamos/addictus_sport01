<?php
require_once __DIR__ . "/Libraries/Core/DotEnv.php";

// Detectar entorno automáticamente
$host = $_SERVER['HTTP_HOST'] ?? 'cli';
$isLocal = strpos($host, 'localhost') !== false || in_array($host, ['127.0.0.1', '::1']);
$envFile = $isLocal ? '.env.local' : '.env.produccion';

(new DotEnv(__DIR__ . "/$envFile"));

// Zona horaria
date_default_timezone_set(getenv('APP_TIMEZONE') ?: 'America/Mexico_City');

// Configuración y helpers
require_once __DIR__ . "/Config/Config.php";
require_once __DIR__ . "/Helpers/Helpers.php";
