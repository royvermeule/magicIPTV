<?php

declare(strict_types=1);

namespace Src\entities;

use Doctrine\ORM\Mapping as ORM;
#[ORM\Entity]
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

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $pass_key = null;

    #[ORM\Column(type: 'datetime', length: 255)]
    private \DateTime $created_at;

    #[ORM\Column(type: 'datetime', length: 255, nullable: true)]
    private ?\DateTime $updated_at = null;

    public function __construct(string $name, ?string $pass_key = null)
    {
        $this->name = $name;
        $this->pass_key = $pass_key;
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