<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Src\core\http\routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class RouterTest extends TestCase
{
    private Router $router;

    public function setUp(): void
    {
        $this->router = new Router();
        $this->router::get('/test', function () {
            return new Response(
                'test',
            );
        });
    }

    public function testResponse(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/test';

        $response = $this->router::run();
        $this->assertEquals('test', $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = '/test';

        $response = $this->router::run();
        $this->assertEquals(404, $response->getStatusCode());
    }
}