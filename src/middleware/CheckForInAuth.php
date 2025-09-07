<?php

declare(strict_types=1);

namespace Src\middleware;

use Src\core\http\routing\IMiddleware;
use Src\core\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class CheckForInAuth implements IMiddleware
{
    #[\Override]
    public function handle(Request $_request, array $_params): Response
    {
        $inAuth = Session::get('in_auth');
        if ($inAuth === null) {
            return new RedirectResponse(
                url: '/login',
            );
        }

        return new Response();
    }
}