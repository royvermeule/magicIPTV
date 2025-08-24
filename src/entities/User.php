<?php

declare(strict_types=1);

namespace Src\entities;

use Couchbase\Role;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Src\repositories\UserRepository;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Roles::class, inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private Roles $role;

    #[ORM\Column(type: 'string', length: 255)]
    private string $email;

    #[ORM\Column(type: 'string', length: 255)]
    private string $password;

    #[ORM\Column(type: 'string', length: 2)]
    private string $language;

    #[ORM\Column(type: 'boolean', length: 255)]
    private bool $isVerified = false;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $created_at;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $updated_at = null;

    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'id', cascade: ['persist', 'remove'])]
    private Collection $profiles;

    public function __construct(
        string $email,
        string $password,
        Roles $role,
        string $language
    ) {
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        $this->role = $role;
        $this->created_at = new \DateTime();
        $this->profiles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function checkPassword(string $passwordToCompare): bool
    {
        return password_verify($passwordToCompare, $this->password);
    }

    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): void
    {
        $this->updated_at = $updatedAt;
    }

    public function getRole(): ?Roles
    {
        return $this->role;
    }

    public function setRole(Roles $role): void
    {
        $this->role = $role;
    }

    public function setIsVerified(bool $isVerified): void
    {
        $this->isVerified = $isVerified;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function getProfiles(): Collection
    {
        return $this->profiles;
    }
}