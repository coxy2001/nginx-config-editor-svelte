<?php

/* ========== Server Init ========== */

// Display Errors
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Class autoloader
spl_autoload_register(function ($class_name) {
    require $class_name . '.php';
});

// CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(204);
    exit;
}

// Authentication
require 'config.php';
if (
    !(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])
        && $_SERVER['PHP_AUTH_USER'] === ADMIN_USERNAME
        && $_SERVER['PHP_AUTH_PW'] === ADMIN_PASSWORD)
) {
    header('WWW-Authenticate: Basic');
    header('HTTP/1.0 401 Unauthorized');
    echo "Login required";
    exit;
}

/* ========== Handle Request ========== */

// Get request URL
$url =  "//" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
$urlComponents = parse_url($url);
$pathComponents = explode("/", $urlComponents["path"]);

$request = "";
if (sizeof($pathComponents) > 1)
    $request = $pathComponents[1];

$file = "";
if (sizeof($pathComponents) > 2)
    $file = $pathComponents[2];

// Process request
if ($request === "") {
    Routes::index();
} elseif ($request === "restart") {
    Routes::restart();
} elseif ($request === "available") {
    Routes::available();
} elseif ($request === "enabled") {
    Routes::enabled();
} elseif ($file !== "") {
    if ($request === "file") {
        Routes::file($file);
    } elseif ($request === "enable") {
        Routes::enable($file);
    } elseif ($request === "disable") {
        Routes::disable($file);
    }
} else {
    http_response_code(404);
}
