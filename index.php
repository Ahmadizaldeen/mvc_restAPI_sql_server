<?php
declare(strict_types=1);

require_once __DIR__ ."/app/helpers/bootstrap.php";
require_once __DIR__ . '/app/core/Database.php';
require_once __DIR__ . '/app/core/Response.php';
require_once __DIR__ . '/app/models/Todo.php';
require_once __DIR__ . '/app/controllers/TodoController.php';
require_once __DIR__ . '/app/core/Router.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    http_response_code(200);
    exit;
}

header('Content-Type: application/json; charset=utf-8'); //JSON als Antwort
header('Access-Control-Allow-Origin: *');//Erlaubt Anfragen von anderen Domains (CORS).
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');//Erlaubt Content-Type: application/json

// Methode + URL einlesen
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = preg_replace(
    '#^' . preg_quote(BASE_PATH, '#') . '/?#',
    '',
    $uri
);

$uri = trim($uri, '/');
// Router startet
$router = new Router($method, $uri);
#var_dump($router);
$router->dispatch();
?>