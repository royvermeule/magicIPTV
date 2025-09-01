<?php

namespace Src\repositories;

use Doctrine\ORM\EntityRepository;
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
}