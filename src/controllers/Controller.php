<?php

namespace Src\controllers;

use Src\core\http\IController;
use Src\core\http\IsController;
use Src\entities\Roles;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final class Controller implements IController
{
    use IsController;

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     */
    public function index(): Response
    {
        return $this->view('index');
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     */
    public function home(): Response
    {
        return $this->view('home');
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     */
    public function login(Request $request): Response
    {
        $referer = $request->headers->get('referer') ?? '/home';
        $host = $request->getSchemeAndHttpHost();
        if (!str_starts_with($referer, $host)) {
            $referer = '/home';
        }

        $params = [
            'referer' => $referer,
        ];

        return $this->view('auth/login', $params);
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function register(): Response
    {
        return $this->view('auth/register');
    }
}