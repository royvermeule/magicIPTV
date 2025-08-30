<?php

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__. '/../');
$dotenv->load();

return [
    'BASE_URL' => 'http://localhost:8080',
    'DB_HOST' => 'localhost',
    'DB_NAME' => $_ENV['DB_NAME'],
    'DB_USER' => $_ENV['DB_USER'],
    'DB_PASS' => $_ENV['DB_PASS'],
    'DB_PORT' => $_ENV['DB_PORT'],
    'DB_DRIVER' => $_ENV['DB_DRIVER'],
    'DB_CHARSET' => 'utf8',
    'DB_FILE' => '',
];
