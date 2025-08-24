<?php

declare(strict_types=1);

use Src\controllers\AuthController;
use Src\controllers\Controller;
use Src\core\http\routing\Router;
use Src\core\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

Router::get('/', [Controller::class, 'index']);
Router::get('/home', [Controller::class, 'home']);

Router::get('/login', [Controller::class, 'login']);
Router::post('/login', [AuthController::class, 'login']);

Router::get('/register', [Controller::class, 'register']);
Router::post('/register', [AuthController::class, 'register']);

Router::addMiddlewareToAllRoutes(function (Request $request) {
    $uri = $request->getRequestUri();
    $userExists = Session::has('user_id');
    if (!$userExists) {
        return match ($uri) {
            default => new RedirectResponse('/login'),
            '/login', '/register' => new Response(),
        };
    }

    $referer = $request->headers->get('referer') ?? '/home';
    $host = $request->getSchemeAndHttpHost();

    if (!str_starts_with($referer, $host)) {
        $referer = '/home';
    }

    return match ($uri) {
        '/login', '/register' => new RedirectResponse($referer),
        default => new Response(),
    };
});

Router::addMiddlewareToAllRoutes(function (Request $request) {
    $csrfToken = $request->headers->get('csrf_token');
    if (
        $csrfToken === null ||
        $csrfToken !== Session::get('csrf_token')
    ) {
        return new Response(
            content: 'Invalid CSRF token',
        );
    }
    return new Response();
}, 'POST');
