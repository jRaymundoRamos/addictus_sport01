<?php
require_once __DIR__ . "/bootstrap.php";

$url = $_GET['url'] ?? 'home/home';
$arrUrl = explode("/", filter_var($url, FILTER_SANITIZE_URL));

$controller = strtolower($arrUrl[0] ?? 'home');
$method = $arrUrl[1] ?? 'index';
$params = array_slice($arrUrl, 2);

require_once __DIR__ . "/Libraries/Core/Autoload.php";
require_once __DIR__ . "/Libraries/Core/Load.php";
