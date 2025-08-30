<?php

declare(strict_types=1);

use Src\controllers\AuthController;
use Src\controllers\Controller;
use Src\core\Config;
use Src\core\http\routing\Router;
use Src\core\Session;
use Src\entities\RegistrationTokens;
use Src\language\errors\AuthError;
use Src\repositories\RegistrationTokenRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

Router::get('/', [Controller::class, 'index']);
Router::get('/home', [Controller::class, 'home']);

Router::get('/login', [Controller::class, 'login']);
Router::post('/login', [AuthController::class, 'login']);

Router::get('/register', [Controller::class, 'register']);
Router::post('/register', [AuthController::class, 'register']);
Router::get('/verify-account/{token}', [AuthController::class, 'verifyAccount']);
Router::get('/logout', [AuthController::class, 'logout']);

Router::addMiddlewareToAllRoutes(function (Request $request) {
    $csrfToken = $request->get('csrf_token');
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
