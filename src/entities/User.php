<?php

declare(strict_types=1);

namespace Src\entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
final class User
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255)]
    private string $email;

    public function __construct(string $name, string $email)
    {
        $this->name  = $name;
        $this->email = $email;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): string
    {
        return $this->email;
    }

    // optional setter if you want to allow name changes
    public function rename(string $name): void
    {
        $this->name = $name;
    }
}
