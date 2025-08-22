<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Src\core\http\IController;
use Src\core\http\IsController;
use Src\core\http\routing\Route;
use Symfony\Component\HttpFoundation\Response;

final class RouteTest extends TestCase
{
    public function testCallableCallback(): void
    {
        $route = new Route(
            'GET',
            '/test',
            function (): Response {
                return new Response('test');
            }
        );

        $match = $route->match('GET', '/not-test');
        $this->assertFalse($match);

        $match = $route->match('POST', '/not-test');
        $this->assertFalse($match);

        $match = $route->match('POST', '/test');
        $this->assertFalse($match);

        $match = $route->match('GET', '/test');
        $this->assertTrue($match);

        $response = $route->call();
        $this->assertEquals('test', $response->getContent());
    }

    public function testControllerCallback(): void
    {
        $class = new class implements IController {
            use IsController;

            public function index(): Response
            {
                return new Response('test');
            }
        };

        $route = new Route(
            'GET',
            '/test',
            [$class::class, 'index']
        );

        $response = $route->call();
        $this->assertEquals('test', $response->getContent());
    }

    public function testParams(): void
    {
        $route = new Route(
            'GET',
            '/test/{id}',
            function (int $id): Response {
                return new Response("$id");
            }
        );
        $match = $route->match('GET', '/test');
        $this->assertFalse($match);

        $match = $route->match('GET', '/test/123');
        $this->assertTrue($match);

        $response = $route->call();
        $this->assertEquals('123', $response->getContent());
    }
}