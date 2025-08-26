<?php

declare(strict_types=1);

namespace Src\repositories;

use Doctrine\ORM\EntityRepository;
use Src\entities\User;

/**
 * @extends EntityRepository<User>
 */
final class UserRepository extends EntityRepository
{
    public function findByEmail(string $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }

    public function authenticate(string $email, string $password): ?User
    {
        $user = $this->findByEmail($email);
        if ($user === null) {
            return null;
        }
        if ($user->checkPassword($password) === false) {
            return null;
        }

        return $user;
    }

    /**
     * @throws \Exception
     */
    public function verify(string $email): void
    {
        $user = $this->findOneBy(['email' => $email]);
        if ($user === null) {
            throw new \Exception('User not found');
        }
        $user->setIsVerified(true);
    }
}
