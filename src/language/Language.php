<?php

declare(strict_types=1);

namespace Src\language;

use Src\core\Config;
use Src\core\Session;
use Src\entities\User;
use Src\repositories\UserRepository;

enum Language: string
{
    case EN = 'en';
    case NL = 'nl';
    case ES = 'es';
    case DE = 'de';
    case FR = 'fr';

    /**
     * @throws \Exception
     */
    public static function current(): self
    {
        /** @var string $lang */
        $lang = Session::get('language') ?? 'en';

        if (Session::has('user_id')) {
            $entityManager = Config::getEntityManager();
            /** @var UserRepository $userRepo */
            $userRepo = $entityManager->getRepository(User::class);
            $user = $userRepo->find(Session::get('user_id'));
            if ($user === null) {
                throw new \Exception('User not found');
            }
            $lang = $user->getLanguage();
        }

        return self::tryFrom($lang) ?? self::EN;
    }
}
