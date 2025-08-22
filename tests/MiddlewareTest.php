<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Src\core\http\routing\IMiddleware;
use Src\core\http\routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class MiddlewareTest extends TestCase
{
    private Router $router;

    /**
     * @throws \Exception
     */
    public function setUp(): void
    {
        $this->router = new Router();
        $this->router::get('/test-middleware-callable/{test}', function (string $test) {
            return new Response('test');
        });

        $this->router::addMiddleware(
            method: 'GET',
            name: '/test-middleware-callable/{test}',
            middleware: function (Request $request, array $params): Response {
            if ($params['test'] === 'test_fails') {
                return new Response(
                    status: 401
                );
            }
            return new Response();
        });

        $this->router::get('/test-middleware-class/{test}', function (string $test) {
            return new Response('test');
        });

        $this->router::addMiddleware(
            method: 'GET',
            name: '/test-middleware-class/{test}',
            middleware: new class implements IMiddleware {
                public function handle(Request $request, array $params): Response
                {
                    if ($params['test'] === 'test_fails') {
                        return new Response(
                            status: 401
                        );
                    }
                    return new Response();
                }
            }::class);
    }

    public function testMiddlewareCallable(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/test-middleware-callable/test';
        $response = $this->router::run();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('test', $response->getContent());

        $_SERVER['REQUEST_URI'] = '/test-middleware-callable/test_fails';
        $response = $this->router::run();
        $this->assertEquals(401, $response->getStatusCode());
    }
}