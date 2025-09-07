<?php

namespace Src\repositories;

use Doctrine\ORM\EntityRepository;
use OTPHP\TOTP;
use Src\entities\AuthTokens;
use Src\entities\User;

/**
 * @extends EntityRepository<AuthTokens>
 */
class AuthTokenRepository extends EntityRepository
{
    public function findByUser(User $user): ?AuthTokens
    {
        return $this->findOneBy(['user' => $user]);
    }

    public function findByToken(string $token): ?AuthTokens
    {
        return $this->findOneBy(['token' => $token]);
    }
}