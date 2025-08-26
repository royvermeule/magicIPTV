<?php

declare(strict_types=1);

namespace Src\entities;

use Doctrine\ORM\Mapping as ORM;
use Random\RandomException;
use Src\repositories\RegistrationTokenRepository;

#[ORM\Entity(repositoryClass: RegistrationTokenRepository::class)]
class RegistrationTokens
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\OneToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\Column(type: 'string', length: 255)]
    private string $token;

    #[ORM\Column(type: 'datetime', length: 255)]
    private \DateTime $createdAt;

    /**
     * @throws RandomException
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->token = bin2hex(random_bytes(16));
        $this->createdAt = new \DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}