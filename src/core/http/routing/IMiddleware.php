<?php

declare(strict_types=1);

namespace Src\core\http\routing;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface IMiddleware
{
    public function handle(Request $request, array $params): Response;
}