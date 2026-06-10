<?php

require_once __DIR__ . '/../../config/database.php';
$_scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
#var_dump($_scheme);
$_root = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '/');
#var_dump($_root);
$_dir = str_replace(
    '\\',
    '/',
    realpath(__DIR__ . '/../../')
);
#var_dump(dirname(__DIR__));
#var_dump($_dir);
define('BASE_URL',
    $_scheme . '://' . $_SERVER['HTTP_HOST']
    . str_replace($_root, '', $_dir)
);

define('BASE_PATH',
    str_replace($_root, '', $_dir)
);
unset($_scheme, $_root, $_dir);
?>