<?php

declare(strict_types=1);

namespace Src\entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Src\repositories\RoleRepository;

#[ORM\Entity(repositoryClass: RoleRepository::class)]
class Roles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $created_at;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $updated_at = null;

    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'role', cascade: ['persist', 'remove'])]
    private Collection $users;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->created_at = new \DateTime();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): void
    {
        $this->users->add($user);
        $user->setRole($this);
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTime $updated_at): void
    {
        $this->updated_at = $updated_at;
    }
}
