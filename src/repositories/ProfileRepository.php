<?php

declare(strict_types=1);

namespace Src\repositories;

use Doctrine\ORM\EntityRepository;
use Src\entities\Profiles;
use Src\entities\User;

/** @extends EntityRepository<Profiles> */
final class ProfileRepository extends EntityRepository
{
    public function findProfileByName(string $name): ?Profiles
    {
        return $this->findOneBy(['name' => $name]);
    }

    public function findProfileByM3uLink(string $m3uLink): ?Profiles
    {
        return $this->findOneBy(['m3u_link' => $m3uLink]);
    }
}