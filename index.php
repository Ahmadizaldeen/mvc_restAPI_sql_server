<?php
declare(strict_types=1);
use Dotenv\Dotenv;

require_once __DIR__ .'/vendor/autoload.php';
$dotenv = Dotenv::createImmutable(__DIR__);// .env suchen in __DIR__
$dotenv->load(); // Variable jetzt in $_ENV['']  zugreifbar
#print_r ($_ENV);

require_once __DIR__ ."/app/helpers/bootstrap.php";
require_once __DIR__. '/app/controllers/HomeController.php'; // home response
require_once __DIR__ . '/app/core/Database.php';
#require_once __DIR__ . '/app/core/Request.php';
require_once __DIR__ . '/app/core/Response.php';
require_once __DIR__ .'/app/models/User.php'; // model laden vor controller
require_once __DIR__. '/app/controllers/AuthController.php';
require_once __DIR__ . '/app/models/Todo.php';
require_once __DIR__ . '/app/controllers/TodoController.php';
require_once __DIR__ . '/app/middleware/AuthMiddleware.php';// private routes prüfen
require_once __DIR__ . '/app/core/RouteRegistry.php';   // PROJEKT URL.
require_once __DIR__ . '/app/core/Router.php';




if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {  // CORS-Preflight
    sendCorsHeaders(includeOptions: true); // CORS_Header für Preflight 
    http_response_code(200);
    exit;
}

sendCorsHeaders(); // CORS-Header für alle Requests senden
// Methode + URL einlesen
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = preg_replace(
    '#^' . preg_quote(BASE_PATH, '#') . '/?#',
    '',
    $uri
);
$uri = trim($uri, '/');

// $request = new Request($method, $uri);
// $request->handle_request();// public/private/notfound check

// Router startet
$router = new Router($method, $uri);
#var_dump($router);
$router->dispatch();
?>