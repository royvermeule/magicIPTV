<?php

declare(strict_types=1);

namespace Src\middleware;

use Src\core\http\routing\IMiddleware;
use Src\core\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class WhenNotLoggedIn implements IMiddleware
{
    public function handle(Request $_request, array $_params): Response
    {
        if (!Session::has('user_id')) {
            return new RedirectResponse('/login');
        }

        return new Response;
    }
}