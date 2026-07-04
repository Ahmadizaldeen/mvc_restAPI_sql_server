<?php
function dd(... $data)
{
    if (($_ENV['APP_ENV'] ?? '') === 'local') {
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
    header('Access-Control-Allow-Origin: *'); // public API erreichtbar für alle Domine
    $methods = $includeOptions
        ? 'GET, POST, PUT, DELETE, OPTIONS'
        : 'GET, POST, PUT, DELETE';
    header("Access-Control-Allow-Methods: $methods"); // Erlaubt Methoden für CORS-Anfragen
    header('Access-Control-Allow-Headers: Content-Type, Authorization');//Erlaubt Content-Type: application/json
    header('Access-Control-Max-Age: 86400'); // Cache für Preflight-Anfragen (1 Tag)
}
?>