<?php

namespace Src\controllers;

use Src\core\http\IController;
use Src\core\http\IsController;
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
        return $this->view('index', ['message' => 'Hello World']);
    }

    public function getMessage(): Response
    {
        return $this->json(['message' => 'Hello World']);
    }
}