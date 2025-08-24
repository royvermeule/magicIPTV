<?php

declare(strict_types=1);

namespace Src\core\http;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Src\core\Config;
use Src\core\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

trait IsController
{
    private EntityManagerInterface $entityManager;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->entityManager = Config::getEntityManager();
    }

    private function json(
        array $data,
        int $status = 200,
        array $headers = [],
    ): Response
    {
        $applicationJson = ['Content-Type' => 'application/json'];
        $headers = array_merge($headers, $applicationJson);

        $json = json_encode($data);
        if ($json === false) {
            throw new \RuntimeException('Failed to encode json');
        }
        return new Response($json, $status, $headers);
    }

    /**
     * @param string $file
     * @param array<string, scalar> $params
     * @param array<string, string> $headers
     * @return Response
     * @throws RuntimeError
     * @throws SyntaxError
     */
    private function view(string $file, array $params = [], array $headers = []): Response
    {
        $loader = new FilesystemLoader(__DIR__ . '/../../views/');
        $twig = new Environment($loader);

        $standardParams = [
            'csrf_token' => Session::get('csrf_token')
        ];
        $params = array_merge($standardParams, $params);

        try {
            $content = $twig->render($file . '.twig', $params);
        } catch (LoaderError $e) {
            return new Response(
                content: $e->getMessage(),
                status: 404,
            );
        }
        return new Response($content, 200, $headers);
    }
}