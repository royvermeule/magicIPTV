<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Src\core\Config;

class LocalConfigTest extends TestCase
{
    public function testAppRoot(): void
    {
        $this->assertDirectoryExists(Config::$appRoot);
    }

    /**
     * @throws \Exception
     */
    public function testGetLocalConfig(): void
    {
        $localConfig = Config::getLocalConfig();
        $this->assertIsArray($localConfig);
        $this->assertArrayHasKey('BASE_URL', $localConfig);
        $this->assertIsString($localConfig['BASE_URL']);
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public function testGetFromLocalConfig(): void
    {
        $baseUrl = Config::getFromLocalConfig('BASE_URL');
        $this->assertIsString($baseUrl);
    }

    /**
     * @throws \Exception
     */
    public function testGetFromLocalConfigThrowsOnInvalidKey(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Key INVALID_KEY not found in local-config.php');

        Config::getFromLocalConfig('INVALID_KEY');
    }
}