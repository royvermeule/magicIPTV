<?php

namespace Src\repositories;

use Doctrine\ORM\EntityRepository;
use Src\entities\Roles;

/**
 * @extends EntityRepository<Roles>
 */
final class RoleRepository extends EntityRepository
{
    public function getByName(string $name): ?Roles
    {
        return $this->findOneBy(['name' => $name]);
    }
}