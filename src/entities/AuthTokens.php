<?php

declare(strict_types=1);

namespace Src\entities;

use Doctrine\ORM\Mapping as ORM;
use Src\repositories\AuthTokenRepository;

#[ORM\Entity(repositoryClass: AuthTokenRepository::class)]
class AuthTokens
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\OneToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\Column(type: 'string', length: 255)]
    private string $token;

    #[ORM\Column(type: 'datetime', length: 255)]
    private \DateTime $created_at;

    public function __construct(string $token, User $user)
    {
        $this->token = $token;
        $this->user = $user;
        $this->created_at = new \DateTime();
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->created_at;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }
}