<?php

namespace Src\core;

use Src\core\http\routing\Router;
use Src\entities\User;

final class Application
{
    private function requireRoutes(): void
    {
        $dir = Config::$appRoot . 'routes/';
        $files = glob($dir . '*.php');
        if ($files === false) {
            return;
        }
        foreach ($files as $file) {
            if (!is_file($file)) {
                continue;
            }
            require_once $file;
        }
    }

    public function run(): void
    {
        $this->requireRoutes();
        $response = Router::run();
        $response->send();
    }
}