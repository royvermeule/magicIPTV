<?php

declare(strict_types=1);

namespace Src\controllers;

use Src\core\http\IController;
use Src\core\http\IsController;
use Src\core\Session;
use Src\entities\Roles;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final class Controller implements IController
{
    use IsController;

    /**
     * @throws \Exception
     */
    public function index(): Response
    {
        return $this->view('home');
    }

    /**
     * @throws \Exception
     */
    public function home(): Response
    {
        return $this->view('home');
    }

    /**
     * @throws \Exception
     */
    public function login(Request $request): Response
    {
        return $this->view('auth.login');
    }

    public function register(): Response
    {
        return $this->view('auth.register');
    }
}