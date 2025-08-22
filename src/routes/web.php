<?php

declare(strict_types=1);

use Src\controllers\Controller;
use Src\core\http\routing\Router;

Router::get('/', [Controller::class, 'index']);
Router::get('/get-message', [Controller::class, 'getMessage']);
