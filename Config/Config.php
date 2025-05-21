<?php
// Base del sistema
define('BASE_URL', getenv('BASE_URL'));
define('NOMBRE_EMPRESA', getenv('NOMBRE_EMPRESA'));

// Configuración base de datos
define('DB_HOST', getenv('DB_HOST'));
define('DB_NAME', getenv('DB_NAME'));
define('DB_USER', getenv('DB_USER'));
define('DB_PASSWORD', getenv('DB_PASSWORD'));
define('DB_CHARSET', getenv('DB_CHARSET'));

// Separadores numéricos
define('SPD', getenv('SPD')); // Separador de decimales
define('SPM', getenv('SPM')); // Separador de miles

// Símbolo monetario
define('SMONEY', getenv('SMONEY'));

// Configuración de correo
define('NOMBRE_REMITENTE', getenv('NOMBRE_REMITENTE'));
define('EMAIL_REMITENTE', getenv('EMAIL_REMITENTE'));
define('NOMBRE_EMPESA', getenv('NOMBRE_EMPESA'));
define('WEB_EMPRESA', getenv('WEB_EMPRESA'));

// Cifrado
define('KEY', getenv('KEY'));
define('METHODENCRIPT', getenv('METHODENCRIPT'));

// Configuración de sliders o banners
define('CAT_SLIDER', explode(',', getenv('CAT_SLIDER')));
define('CAT_BANNER', explode(',', getenv('CAT_BANNER')));

// Otros
define('COSTOENVIO', floatval(getenv('COSTOENVIO')));
