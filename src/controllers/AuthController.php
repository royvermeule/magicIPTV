<?php

namespace Src\controllers;

use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use PHPMailer\PHPMailer\PHPMailer;
use Random\RandomException;
use Respect\Validation\Validator;
use Src\core\Config;
use Src\core\http\IController;
use Src\core\http\IsController;
use Src\core\Session;
use Src\entities\RegistrationTokens;
use Src\entities\Roles;
use Src\entities\User;
use Src\language\emails\AuthEmail;
use Src\language\errors\AuthError;
use Src\language\messages\AuthMessage;
use Src\repositories\RegistrationTokenRepository;
use Src\repositories\RoleRepository;
use Src\repositories\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

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

        $referer = (string) $request->request->get('referer');
        return new RedirectResponse($referer);
    }

    /**
     * @throws \Exception
     * @throws ORMException
     */
    public function register(Request $request): Response
    {
        $email = (string) $request->request->get('email');
        $password = (string) $request->request->get('password');
        $passwordConfirmation = (string) $request->request->get('password_confirmation');

        /** @var array<string, Validator> $userValidator */
        $userValidator = Config::getValidator('user-validator');

        if (!$userValidator['email']->isValid($email)) {
            return new Response(
                content: AuthError::InvalidEmail->translate()
            );
        }
        if (!$userValidator['password']->isValid($password)) {
            return new Response(
                content: AuthError::InvalidPassword->translate()
            );
        }
        if ($password !== $passwordConfirmation) {
            echo $password . "\n" . $passwordConfirmation;
            return new Response(
                content: AuthError::PasswordDoNotMatch->translate()
            );
        }

        /** @var UserRepository $userRepo */
        $userRepo = $this->entityManager->getRepository(User::class);
        if ($userRepo->findByEmail($email) !== null) {
            return new Response(
                content: AuthError::UserAlreadyExists->translate()
            );
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
     * @throws \DateMalformedStringException
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
        $mail = new PHPMailer();

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'roywilliamvermeulen@gmail.com';
        $mail->Password = (string) Config::getFromLocalConfig('GMAIL_APP_PASSWORD');
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('roywilliamvermeulen@gmail.com', 'Roy Vermeulen');
        $mail->addAddress($email);

        $token = $registrationToken->getToken();
        $baseUrl = (string) Config::getFromLocalConfig('BASE_URL');

        $mail->Subject = AuthEmail::VerificationEmailSubject->translate();
        $mail->isHTML(true);
        $mail->Body = AuthEmail::VerificationEmailBody->translate(
            data: ['link' => "$baseUrl/verify-account/$token"]
        );
        $mail->send();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws RuntimeError
     * @throws SyntaxError
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

        return $this->view('/auth/message', [
            'title' => 'Verify',
            'message' => AuthMessage::registrationCompleted->translate()
        ]);
    }

    public function logout(): Response
    {
        Session::unset('user_id');

        return new RedirectResponse('/login');
    }
}