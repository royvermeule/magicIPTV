<?php

declare(strict_types=1);

namespace Src\core\http;

use Doctrine\ORM\EntityManagerInterface;
use eftec\bladeone\BladeOne;
use PHPMailer\PHPMailer\PHPMailer;
use Src\core\Config;
use Src\core\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

trait IsController
{
    private EntityManagerInterface $entityManager;

    private PHPMailer $mail;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->entityManager = Config::getEntityManager();
        $this->mail = new PHPMailer(true);

        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = Config::getFromLocalConfig('GMAIL_EMAIL');
        $this->mail->Password = (string) Config::getFromLocalConfig('GMAIL_APP_PASSWORD');
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port = 587;

        $this->mail->setFrom(Config::getFromLocalConfig('GMAIL_EMAIL'), 'MagicIPTV');
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
     */

    private function view(string $file, array $params = [], array $headers = []): Response
    {
        $views = __DIR__ . '/../../views';
        $cache = __DIR__ . '/../../cache';
        if (!is_dir($cache)) {
            mkdir($cache, 0775, true);
        }

        $blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);

        $standardParams = [
            'csrf_token' => Session::get('csrf_token'),
            'request' => Request::createFromGlobals()
        ];
        $params = array_merge($standardParams, $params);

        try {
            $content = $blade->run($file, $params);

        } catch (\Exception $e) {
            return new Response(
                content: $e->getMessage(),
                status: 404,
            );
        }

        return new Response($content, 200, $headers);
    }

    private function hxRedirect(string $url): Response
    {
        return new Response(
            headers: ['HX-Redirect' => $url],
        );
    }
}