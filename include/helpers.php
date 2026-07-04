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
?>