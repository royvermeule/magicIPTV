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

    #[ORM\Column(type: 'integer', length: 1)]
    private int $attempts;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $created_at;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $expired_at;

    /**
     * @throws \DateMalformedStringException
     */
    public function __construct(string $token, User $user)
    {
        $this->token = $token;
        $this->user = $user;
        $this->attempts = 0;
        $this->created_at = new \DateTime();
        $expiresAt = new \DateTime();
        $expiresAt->add(new \DateInterval('PT20M'));

        $this->expired_at = $expiresAt;
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

    public function getAttempts(): int
    {
        return $this->attempts;
    }

    public function setAttempts(int $attempts): void
    {
        $this->attempts = $attempts;
    }

    public function getExpiredAt(): \DateTime
    {
        return $this->expired_at;
    }

    public function setExpiredAt(\DateTime $expired_at): void
    {
        $this->expired_at = $expired_at;
    }
}