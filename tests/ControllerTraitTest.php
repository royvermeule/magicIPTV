<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Src\core\http\IController;
use Src\core\http\IsController;
use Symfony\Component\HttpFoundation\Response;

final class ControllerTraitTest extends TestCase
{
    private IController $controller;

    protected function setUp(): void
    {
        $this->controller = new class implements IController {
            use IsController;
        };
    }

    /**
     * @throws \ReflectionException
     */
    public function testJson(): void
    {
        $reflectionClass = new \ReflectionClass($this->controller);
        $method = $reflectionClass->getMethod('json');

        /** @var Response $response */
        $response = $method->invoke($this->controller, ['test' => 'test']);
        $this->assertEquals('{"test":"test"}', $response->getContent());

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Failed to encode json');

        $brokenUtf8 = "\xB1\x31";
        $method->invoke($this->controller, ['bad' => $brokenUtf8]);
    }
}