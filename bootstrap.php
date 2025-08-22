<?php

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Src\core\Config;

require_once __DIR__ . "/vendor/autoload.php";

$ormConfig = ORMSetup::createAttributeMetadataConfig(
    paths: [__DIR__ . '/src/entities'],
    isDevMode: true,
);

$proxyDir = __DIR__ . '/var/doctrine/proxies';
if (!is_dir($proxyDir)) {
    mkdir($proxyDir, 0777, true);
}
$ormConfig->setProxyDir($proxyDir);
$ormConfig->setProxyNamespace('DoctrineProxies');
$ormConfig->setAutoGenerateProxyClasses(true);

try {
    $config = Config::getLocalConfig();
} catch (Exception $e) {
    die($e);
}

$config['TEST_MODE'] = $config['TEST_MODE'] ?? false;

$params = [
    'driver'   => $config['DB_DRIVER'],
    'host'     => $config['DB_HOST'] ?? '',
    'port'     => $config['DB_PORT'] ?? '',
    'dbname'   => $config['DB_NAME'] ?? '',
    'user'     => $config['DB_USER'] ?? '',
    'password' => $config['DB_PASS'] ?? '',
    'charset'  => $config['DB_CHARSET'] ?? '',
];

if ($config['TEST_MODE'] === true) {
    $params = [
        'driver' => $config['DB_DRIVER'],
        'path'   => $config['DB_FILE'],
    ];
}

if ($config['DB_DRIVER'] === 'pdo_sqlite' && $config['TEST_MODE'] === false) {
    if (empty($config['DB_FILE'])) {
        die('DB_FILE is not set for sqlite driver');
    }

    $params = [
        'driver' => 'pdo_sqlite',
        'path'   => $config['DB_FILE'],
    ];
}

$connection = DriverManager::getConnection($params, $ormConfig);

return new EntityManager($connection, $ormConfig);
