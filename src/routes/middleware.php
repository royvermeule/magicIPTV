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

Router::addMiddleware('GET', '/', function () {
    if (!Session::has('user_id')) {
        return new RedirectResponse('/login');
    } else {
        return new RedirectResponse('/home');
    }
});
Router::addMiddleware('GET', '/home', function () {
    if (!Session::has('user_id')) {
        return new RedirectResponse('/login');
    }

    return new Response();
});

Router::addMiddleware('GET', '/login', function () {
    if (Session::has('user_id')) {
        return new RedirectResponse('/home');
    }

    return new Response();
});
Router::addMiddleware('POST', '/login', function () {
    if (Session::has('user_id')) {
        return new Response(
            status: Response::HTTP_UNAUTHORIZED,
        );
    }

    return new Response();
});

Router::addMiddleware(
    method: 'GET',
    name: '/verify-account/{token}',
    middleware: function (Request $_request, array $params): Response {

        $token = (string) $params['token'];

        $entityManager = Config::getEntityManager();
        /** @var RegistrationTokenRepository $registrationTokenRepo */
        $registrationTokenRepo = $entityManager->getRepository(RegistrationTokens::class);
        $registrationToken = $registrationTokenRepo->findByToken($token);
        if ($registrationToken === null) {
            return new Response(
                content: AuthError::InvalidRegistrationToken->translate(),
                status: Response::HTTP_NOT_FOUND,
            );
        }

        return new Response();
    });

Router::addMiddleware('GET', '/logout', function () {
    if (!Session::has('user_id')) {
        return new RedirectResponse('/login');
    }
    return new Response();
});