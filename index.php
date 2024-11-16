<?php
declare(strict_types=1);

// Autoload von Composer
require_once __DIR__ . '/vendor/autoload.php';
$config = require 'config/config.php';

use App\Controllers\CustomerRestApiController;
use App\core\Auth;
use App\Core\Response;
use App\Core\HttpStatus;


// Enable error reporting when debugging is active
if ($config['debug']) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

// Route handling
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];


if (str_starts_with($requestUri, '/')) {
    if (!Auth::checkAuthenticateApiKey($config)) {
        Response::error('Unauthorized access', HttpStatus::UNAUTHORIZED);
    }
}

$parts = explode("/", $_SERVER["REQUEST_URI"]);

if ($parts[1] != "customer") {
    http_response_code(404);
    Response::error('Not Found', HttpStatus::NOT_FOUND);
}

$customer_id = !empty($parts[2]) ? (int) $parts[2] : null;

$customerController = new CustomerRestApiController($config);

$customerController->handleRequest((int) $customer_id, $requestMethod);

exit();


