<?php

declare(strict_types=1);

namespace Src\controllers;

use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Respect\Validation\Exceptions\NestedValidationException;
use Src\core\Config;
use Src\core\http\IController;
use Src\core\http\IsController;
use Src\core\Session;
use Src\entities\Profiles;
use Src\entities\User;
use Src\repositories\ProfileRepository;
use Src\repositories\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ProfileController implements IController
{
    use IsController;

    public function getProfiles(): Response
    {
        /** @var UserRepository $userRepo */
        $userRepo = $this->entityManager->getRepository(User::class);
        /** @var ?User $user */
        $user = $userRepo->findOneBy(['id' => (int) Session::get('user_id')]);
        if ($user === null) {
            return new Response(
                status: 500
            );
        }

        $profiles = $user->getProfiles();

        return $this->view('generated.profiles', ['profiles' => $profiles]);
    }

    /**
     * @throws \Exception
     */
    private function validateName(string $name): ?Response
    {
        $profileValidator = Config::getValidator('profile-validator');
        try {
            $profileValidator['name']->assert($name);
        } catch (NestedValidationException $e) {

        }
        return null;
    }

    /**
     * @throws \Exception
     */
    private function validatePassKey(string $passKey): ?Response
    {
        $profileValidator = Config::getValidator('profile-validator');
        try {
            $profileValidator['pass_key']->assert($passKey);
        } catch (NestedValidationException $e) {

        }
        return null;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws \Exception
     */
    public function addProfile(Request $request): Response
    {
        $name = (string) $request->get('name');
        $m3uLink = (string) $request->get('m3u_link');
        $passKey = (string) $request->get('passkey');

        $validatedName = $this->validateName($name);
        if ($validatedName !== null) {
            return $validatedName;
        }

        $validatedPassKey = $this->validatePassKey($passKey);
        if ($passKey === '') {
            $passKey = null;
        } else if ($validatedPassKey !== null) {
            return $validatedPassKey;
        }

        /** @var ProfileRepository $profileRepo */
        $profileRepo = $this->entityManager->getRepository(Profiles::class);
        $profileFromName = $profileRepo->findProfileByName($name);
        if ($profileFromName) {
            return new Response('Profile with this name already exists');
        }
        $profileFromM3uLink = $profileRepo->findProfileByM3uLink($m3uLink);
        if ($profileFromM3uLink) {
            return new Response('Profile with this m3u link already exists');
        }

        /** @var UserRepository $userRepo */
        $userRepo = $this->entityManager->getRepository(User::class);
        $user = $userRepo->findOneBy(['id' => (int) Session::get('user_id')]);

        $profile = new Profiles(
            user: $user,
            name: $name,
            m3uLink: $m3uLink,
            passKey: $passKey
        );
        $this->entityManager->persist($profile);
        $this->entityManager->flush();

        return new Response(
            headers: [
                'HX-Trigger' => 'profile_added'
            ]
        );
    }
}