<?php

namespace Src\core\http\routing;

use Src\core\http\IController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class Router
{
    /** @var list<Route> */
    private static array $routes = [];

    /** @var \WeakMap<Route, list<class-string<IMiddleware>|callable(Request, array): Response>>|null */
    private static ?\WeakMap $middleware = null;

    /**
     * @param string $method
     * @param string $name
     * @param array{0: class-string<IController>, 1: string}|callable(mixed...): Response $handler
     * @return void
     */
    private static function addRoute(string $method, string $name, array|callable $handler): void
    {
        $route = new Route($method, $name, $handler);
        self::$routes[] = $route;
    }

    /**
     * @param string $name
     * @param array{0: class-string<IController>, 1: string}|callable(mixed...): Response $handler
     * @return void
     */
    public static function get(string $name, array|callable $handler): void
    {
        self::addRoute('GET', $name, $handler);
    }

    /**
     * @param string $name
     * @param array{0: class-string<IController>, 1: string}|callable(mixed...): Response $handler
     * @return void
     */
    public static function post(string $name, array|callable $handler): void
    {
        self::addRoute('POST', $name, $handler);
    }

    /**
     * @param string $name
     * @param array{0: class-string<IController>, 1: string}|callable(mixed...): Response $handler
     * @return void
     */
    public static function delete(string $name, array|callable $handler): void
    {
        self::addRoute('DELETE', $name, $handler);
    }

    /**
     * @param string $name
     * @param array{0: class-string<IController>, 1: string}|callable(mixed...): Response $handler
     * @return void
     */
    public static function put(string $name, array|callable $handler): void
    {
        self::addRoute('PUT', $name, $handler);
    }

    /**
     * @param string $name
     * @param array{0: class-string<IController>, 1: string}|callable(mixed...): Response $handler
     * @return void
     */
    public static function options(string $name, array|callable $handler): void
    {
        self::addRoute('OPTIONS', $name, $handler);
    }

    /**
     * @param class-string<IMiddleware>|callable(Request, array): Response $middleware
     * @return void
     */
    public static function addMiddlewareToAllRoutes(string|callable $middleware): void
    {
        foreach (self::$routes as $route) {
            if (self::$middleware === null) {
                /** @var \WeakMap<Route, list<class-string<IMiddleware>|(callable(Request, array): Response)>> $map */
                $map = new \WeakMap();
                self::$middleware = $map;
            }
            $map = self::$middleware;

            /** @var list<class-string<IMiddleware>|(callable(Request, array): Response)> $list */
            $list = $map[$route] ?? [];
            $list[] = $middleware;
            $map[$route] = $list;
        }
    }

    /**
     * @param string $method
     * @param string $name
     * @param class-string<IMiddleware>|callable(Request, array): Response $middleware
     * @return void
     * @throws \Exception
     */
    public static function addMiddleware(string $method, string $name, string|callable $middleware): void
    {
        /** @var Route|null $route */
        $route = array_find(self::$routes, function (Route $route) use ($method, $name) {
            return $route->method === $method && $route->name === $name;
        });

        if ($route === null) {
            throw new \Exception('Route not found for middleware');
        }

        if (self::$middleware === null) {
            /** @var \WeakMap<Route, list<class-string<IMiddleware>|(callable(Request, array): Response)>> $map */
            $map = new \WeakMap();
            self::$middleware = $map;
        }
        $map = self::$middleware;

        /** @var list<class-string<IMiddleware>|(callable(Request, array): Response)> $list */
        $list = $map[$route] ?? [];
        $list[] = $middleware;
        $map[$route] = $list;
    }

    private static function runMiddleware(Request $request, Route $route): ?Response
    {
        $map = self::$middleware;
        if ($map === null) {
            return null;
        }

        if (!$map->offsetExists($route)) {
            return null;
        }

        /** @var list<class-string<IMiddleware>|callable(Request, array): Response> $middlewareList */
        $middlewareList = $map[$route];

        foreach ($middlewareList as $middleware) {
            if (is_string($middleware)) {
                if (!is_a($middleware, IMiddleware::class, true)) {
                    throw new \RuntimeException("Middleware class $middleware must implement IMiddleware");
                }
                $instance = new $middleware();
                $response = $instance->handle($request, $route->params);
            } else {
                /** @psalm-var callable(Request, array): Response $middleware */
                $response = $middleware($request, $route->params);
            }

            if ($response->getStatusCode() !== 200) {
                return $response;
            }
        }

        return null;
    }

    public static function run(): Response
    {
        $request = Request::createFromGlobals();

        foreach (self::$routes as $route) {
            if (!$route->match($request->getMethod(), $request->getRequestUri())) {
                continue;
            }

            $response = self::runMiddleware($request, $route);
            if ($response !== null) {
                return $response;
            }

            return $route->call();
        }

        return new Response(
            content: 'Page not found',
            status: 404,
        );
    }
}