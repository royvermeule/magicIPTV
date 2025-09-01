<?php

declare(strict_types=1);

use Src\controllers\AuthController;
use Src\controllers\Controller;
use Src\core\Config;
use Src\core\http\routing\Router;
use Src\core\Session;
use Src\entities\RegistrationTokens;
use Src\language\errors\AuthError;
use Src\repositories\RegistrationTokenRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// ---- main ---- //
Router::get('/', [Controller::class, 'index']);
Router::get('/home', [Controller::class, 'home']);

// ---- auth ---- //
Router::get('/login', [Controller::class, 'login']);
Router::post('/login', [AuthController::class, 'login']);

Router::get('/register', [Controller::class, 'register']);
Router::post('/register', [AuthController::class, 'register']);

Router::get('/verify-account/{token}', [AuthController::class, 'verifyAccount']);
Router::get('/logout', [AuthController::class, 'logout']);

Router::get('/forgot-password', [AuthController::class, 'forgotPassword']);
Router::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Router::post('/forgot-password-send', [AuthController::class, 'forgotPasswordSend']);
Router::get('/forgot-password-verify', [AuthController::class, 'forgotPasswordVerify']);
Router::post('/forgot-password-verify', [AuthController::class, 'forgotPasswordVerify']);
