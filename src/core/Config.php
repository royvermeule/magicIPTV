<?php

declare(strict_types=1);

namespace Src\core;

use Doctrine\ORM\EntityManager;
use Respect\Validation\ChainedValidator;
use Respect\Validation\Validator;

final class Config
{
    public static string $appRoot = __DIR__ . '/../';

    /**
     * @return array<string, scalar>
     * @throws \Exception
     */
    public static function getLocalConfig(): array
    {
        $directory = self::$appRoot . 'local-config.php';
        if (!file_exists($directory)) {
            throw new \Exception('local-config.php not found');
        }
        /** @var array<string, scalar> $localConfig */
        $localConfig = require $directory;
        return $localConfig;
    }

    /**
     * @return scalar
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public static function getFromLocalConfig(string $key): string|int|bool|float
    {
        $localConfig = self::getLocalConfig();
        if (!array_key_exists($key, $localConfig)) {
            throw new \InvalidArgumentException("Key $key not found in local-config.php");
        }
        return $localConfig[$key];
    }

    /**
     * @param string $name
     * @return array
     * @throws \Exception
     */
    public static function getValidator(string $name): array
    {
        $path = self::$appRoot . 'validators/' . $name . '.php';
        if (!file_exists($path)) {
            throw new \Exception('validator ' . $name . ' not found');
        }

        /** @var array<string, Validator> $chainedValidator */
        $chainedValidator = require $path;

        return $chainedValidator;
    }

    /**
     * @throws \Exception
     */
    public static function getEntityManager(): EntityManager
    {
        $entityManager = self::$appRoot . '/../bootstrap.php';
        if (!file_exists($entityManager)) {
            throw new \Exception('bootstrap.php not found');
        }
        /** @var EntityManager $entityManager */
        $entityManager = require $entityManager;
        return $entityManager;
    }
}
