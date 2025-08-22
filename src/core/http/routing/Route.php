<?php

declare(strict_types=1);

namespace Src\core\http\routing;

use Src\core\http\IController;
use Symfony\Component\HttpFoundation\Response;

final class Route
{
    /** @var array<string, string> */
    private(set) array $params = [];
    /**
     * @param string $method
     * @param string $name
     * @param array{0: class-string<IController>, 1: string}|callable(mixed...): Response $handler
     */
    public function __construct(
        public readonly string $method,
        public readonly string $name,
        private readonly mixed $handler
    ) {
    }

    public function match(string $method, string $uri): bool
    {
        if ($method !== $this->method) {
            return false;
        }

        if ($this->name === $uri) {
            return true;
        }

        $nameParts = explode('/', $this->name);
        $uriParts = explode('/', $uri);

        if (count($nameParts) !== count($uriParts)) {
            return false;
        }
        $pattern = '/\{([^}]*)\}/';
        $matches = [];

        foreach ($nameParts as $i => $namePart) {
            $uriPart = $uriParts[$i];
            if ($uriPart === $namePart) {
                continue;
            }

            if (preg_match($pattern, $namePart, $matches)) {
                $this->params[$matches[1]] = $uriPart;
                continue;
            }

            return false;
        }

        return true;
    }

    public function call(): Response
    {
        $handler = $this->handler;

        if (is_array($handler)) {
            /** @var class-string<IController> $classString */
            $classString = $handler[0];
            $method = $handler[1];

            $class = new $classString();
            $handler = [$class, $method];
        }

        if (!is_callable($handler)) {
            throw new \LogicException('Invalid route handler');
        }

        /** @psalm-var callable(mixed...): Response $handler */
        $callable = $handler;

        $response = call_user_func_array($callable, $this->params);

        if (!($response instanceof Response)) {
            throw new \RuntimeException('The route handler must return a Response object');
        }

        return $response;
    }
}
