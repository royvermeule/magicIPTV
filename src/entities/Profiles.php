<?php

declare(strict_types=1);

namespace Src\entities;

use Doctrine\ORM\Mapping as ORM;
use Src\repositories\ProfileRepository;

#[ORM\Entity(repositoryClass: ProfileRepository::class)]
class Profiles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'profiles')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $pass_key;

    #[ORM\Column(type: 'string', length: 255)]
    private string $m3u_link;

    #[ORM\Column(type: 'datetime', length: 255)]
    private \DateTime $created_at;

    #[ORM\Column(type: 'datetime', length: 255, nullable: true)]
    private ?\DateTime $updated_at = null;

    public function __construct(User $user, string $name, string $m3uLink, ?string $passKey = null)
    {
        $this->user = $user;
        $this->name = $name;
        $this->pass_key = $passKey;
        $this->m3u_link = $m3uLink;
        $this->created_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPassKey(): ?string
    {
        return $this->pass_key;
    }

    public function setPassKey(?string $pass_key): void
    {
        $this->pass_key = $pass_key;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setM3uLink(string $m3uLink): void
    {
        $this->m3u_link = $m3uLink;
    }

    public function getM3uLink(): string
    {
        return $this->m3u_link;
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