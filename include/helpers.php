<?php
function dd(... $data)
{
    if ($_ENV['APP_ENV'] === 'local'){
    echo "<pre><br>";
    foreach ($data as $item) {
        var_dump($item);
        echo "\n-----------------\n";
    }

    exit;
    }
}

function get_pattern_ids(string $pattern) { 
$regex = '#^' . preg_replace('/\{[a-z]+\}/', '([0-9]+)', $pattern) . '$#';
return $regex;
}

// include/helpers.php
function sendCorsHeaders(bool $includeOptions = false): void
{
    header('Access-Control-Allow-Origin: *'); //Erlaubt Anfragen von anderen Domains (CORS).
    $methods = $includeOptions
        ? 'GET, POST, PUT, DELETE, OPTIONS'
        : 'GET, POST, PUT, DELETE';
    header("Access-Control-Allow-Methods: $methods");
    header('Access-Control-Allow-Headers: Content-Type, Authorization');//Erlaubt Content-Type: application/json
}
?>