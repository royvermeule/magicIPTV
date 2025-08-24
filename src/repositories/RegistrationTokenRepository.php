<?php

namespace Src\repositories;

use Doctrine\ORM\EntityRepository;
use Src\entities\RegistrationTokens;
use Src\entities\User;

/**
 * @extends EntityRepository<RegistrationTokens>
 */
final class RegistrationTokenRepository extends EntityRepository
{
    public function findByToken(string $token): ?RegistrationTokens
    {
        return $this->findOneBy(['token' => $token]);
    }

    public function findByUser(User $user): ?RegistrationTokens
    {
        return $this->findOneBy(['user' => $user->getId()]);
    }
}