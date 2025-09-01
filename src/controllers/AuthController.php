<?php

declare(strict_types=1);

namespace Src\controllers;

use DateMalformedStringException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use OTPHP\TOTP;
use PHPMailer\PHPMailer\PHPMailer;
use Random\RandomException;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;
use Src\core\Config;
use Src\core\http\IController;
use Src\core\http\IsController;
use Src\core\Session;
use Src\entities\AuthTokens;
use Src\entities\RegistrationTokens;
use Src\entities\Roles;
use Src\entities\User;
use Src\language\emails\AuthEmail;
use Src\language\errors\AuthError;
use Src\language\messages\AuthMessage;
use Src\repositories\AuthTokenRepository;
use Src\repositories\RegistrationTokenRepository;
use Src\repositories\RoleRepository;
use Src\repositories\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class AuthController implements IController
{
    use IsController;

    /**
     * @throws \Exception
     */
    public function login(Request $request): Response
    {
        $email = (string) $request->request->get('email');
        $password = (string) $request->request->get('password');

        /** @var UserRepository $userRepo */
        $userRepo = $this->entityManager->getRepository(User::class);

        $user = $userRepo->authenticate($email, $password);
        if (!$user) {
            return new Response(
                content: AuthError::UserNotFound->translate()
            );
        }
        if ($user->isVerified() === false) {
            return new Response(
                content: AuthError::UserNotVerified->translate()
            );
        }

        Session::set('user_id', (int) $user->getId());

        return $this->hxRedirect('/home');
    }

    /**
     * @throws DateMalformedStringException
     * @throws OptimisticLockException
     * @throws RandomException
     * @throws ORMException
     * @throws \Exception
     */
    public function register(Request $request): Response
    {
        $email = strtolower((string) $request->request->get('email'));
        $password = (string) $request->request->get('password');
        $passwordConfirmation = (string) $request->request->get('password_confirmation');

        /** @var array<string, Validator> $userValidator */
        $userValidator = Config::getValidator('user-validator');

        try {
            $userValidator['email']->assert($email);
        } catch (NestedValidationException $e) {
            $messages = $e->getMessages();
            if (isset($messages['notEmpty'])) {
                return new Response(content: AuthError::EmailEmpty->translate());
            }
            if (isset($messages['email'])) {
                return new Response(content: AuthError::EmailNotValidFormat->translate());
            }
            return new Response(content: AuthError::InvalidEmail->translate());
        }

        try {
            $userValidator['password']->assert($password);
        } catch (NestedValidationException $e) {
            $messages = $e->getMessages();

            if (isset($messages['notEmpty'])) {
                return new Response(content: AuthError::PasswordEmpty->translate());
            }

            if (isset($messages['length'])) {
                if (strlen($password) < 8) {
                    return new Response(content: AuthError::PasswordTooShort->translate());
                }
                if (strlen($password) > 64) {
                    return new Response(content: AuthError::PasswordTooLong->translate());
                }
            }

            if (isset($messages['regex'])) {
                return new Response(content: AuthError::PasswordTooWeak->translate());
            }

            return new Response(content: AuthError::InvalidPassword->translate());
        }

        if ($password !== $passwordConfirmation) {
            return new Response(content: AuthError::PasswordDoNotMatch->translate());
        }

        /** @var UserRepository $userRepo */
        $userRepo = $this->entityManager->getRepository(User::class);
        if ($userRepo->findByEmail($email) !== null) {
            return new Response(content: AuthError::UserAlreadyExists->translate());
        }

        /** @var RoleRepository $roleRepo */
        $roleRepo = $this->entityManager->getRepository(Roles::class);
        $role = $roleRepo->getByName('user');
        if ($role === null) {
            throw new \Exception('Role not found');
        }

        $language = Session::get('language') ?? 'en';
        $user = new User(
            email: $email,
            password: $password,
            role: $role,
            language: (string) $language
        );
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $registrationToken = $this->createRegistrationToken($user);
        $this->sendVerificationEmail($registrationToken, $user->getEmail());

        return new Response(
            content: AuthMessage::verificationLinkSend->translate()
        );
    }

    /**
     * @throws RandomException
     * @throws ORMException
     * @throws DateMalformedStringException
     */
    private function createRegistrationToken(User $user): RegistrationTokens
    {
        /** @var RegistrationTokenRepository $registrationTokenRepo */
        $registrationTokenRepo = $this->entityManager->getRepository(
            RegistrationTokens::class
        );
        $registrationToken = $registrationTokenRepo->findByUser($user);

        if ($registrationToken !== null) {
            $expiresAt = (clone $registrationToken->getCreatedAt()
                ->modify('+60 minutes'));
            $currentTime = new \DateTime();

            if ($expiresAt < $currentTime) {
                return $registrationToken;
            }

            $this->entityManager->remove($registrationToken);
            $this->entityManager->flush();
        }

        $registrationToken = new RegistrationTokens($user);
        $this->entityManager->persist($registrationToken);
        $this->entityManager->flush();

        return $registrationToken;
    }

    /**
     * @throws \Exception
     */
    private function sendVerificationEmail(RegistrationTokens $registrationToken, string $email): void
    {
        $token = $registrationToken->getToken();
        $baseUrl = (string) Config::getFromLocalConfig('BASE_URL');

        $this->mail->addAddress($email);
        $this->mail->Subject = AuthEmail::VerificationEmailSubject->translate();
        $this->mail->isHTML();
        $this->mail->Body = AuthEmail::VerificationEmailBody->translate(
            data: ['link' => "$baseUrl/verify-account/$token"]
        );
        $this->mail->send();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws \Exception
     */
    public function verifyAccount(string $token): Response
    {
        /** @var RegistrationTokenRepository $registrationTokenRepo */
        $registrationTokenRepo = $this->entityManager->getRepository(
            RegistrationTokens::class
        );
        $registrationToken = $registrationTokenRepo->findByToken($token);
        if ($registrationToken === null) {
            return new Response(
                content: AuthError::InvalidRegistrationToken->translate()
            );
        }
        $user = $registrationToken->getUser();
        $user->setIsVerified(true);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->view('auth.message', [
            'title' => 'Verify',
            'message' => AuthMessage::registrationCompleted->translate()
        ]);
    }

    public function logout(): Response
    {
        Session::unset('user_id');

        return new RedirectResponse('/login');
    }

    public function forgotPassword(Request $request): Response
    {
        /** @var ?string $email */
        $email = Session::get('forgot_pwd_email') ?? '';

        $post = $request->isMethod('POST');
        if ($post) {
            $email = (string) $request->request->get('email');
            Session::set('forgot_pwd_email', $email);
            return $this->hxRedirect('/forgot-password');
        }
        Session::unset('forgot_pwd_email');
        return $this->view('auth.forgot-password', ['email' => (string) $email]);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws \Exception
     */
    public function forgotPasswordSend(Request $request): Response
    {
        $email = (string) $request->request->get('email');
        /** @var UserRepository $userRepo */
        $userRepo = $this->entityManager->getRepository(User::class);
        $user = $userRepo->findByEmail($email);
        if ($user === null) {
            return new Response(
                content: AuthMessage::AuthenticationMailSend->translate()
            );
        }

        $authToken = $this->generateAuthToken($user);
        $this->sendAuthToken($authToken, $email);

        return new Response(
            content: AuthMessage::AuthenticationMailSend->translate()
        );
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    private function generateAuthToken(User $user): string
    {
        /** @var AuthTokenRepository $authTokenRepo */
        $authTokenRepo = $this->entityManager->getRepository(AuthTokens::class);

        /** @var ?User $authToken */
        $authToken = $authTokenRepo->findByUser($user);
        if ($authToken !== null) {
            $this->entityManager->remove($authToken);
            $this->entityManager->flush();
        }

        $totp = TOTP::create();

        $authToken = new AuthTokens(
            token: $totp->getSecret(),
            user: $user,
        );
        $this->entityManager->persist($authToken);
        $this->entityManager->flush();

        return $totp->now();
    }

    /**
     * @throws \Exception
     */
    private function sendAuthToken(string $token, string $email): void
    {
        $this->mail->addAddress($email);
        $this->mail->Subject = AuthEmail::AuthCodeEmailSubject->translate();
        $this->mail->isHTML();
        $this->mail->Body = AuthEmail::AuthCodeEmailBody->translate(
            data: ['auth_code' => $token]
        );
        $this->mail->send();
    }

    /**
     * @throws \Exception
     */
    private function verifyAuthToken(string $publicToken, User $user): bool
    {
        /** @var AuthTokenRepository $authTokenRepo */
        $authTokenRepo = $this->entityManager->getRepository(AuthTokens::class);
        $token = $authTokenRepo->findByUser($user);
        if ($token === null) {
            return false;
        }
        $secret = $token->getToken();
        $totp = TOTP::create($secret);

        return $totp->verify($publicToken);
    }


}