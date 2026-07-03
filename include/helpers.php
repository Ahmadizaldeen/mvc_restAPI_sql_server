<?php
function dd(... $data)
{
    echo "<pre><br>";

    foreach ($data as $item) {
        #print_r ("$item: ");
        var_dump($item);
        echo "\n-----------------\n";
    }

    exit;
}

function get_pattern_ids(string $pattern) { 
$regex = '#^' . preg_replace('/\{[a-z]+\}/', '([0-9]+)', $pattern) . '$#';
return $regex;
}
?>