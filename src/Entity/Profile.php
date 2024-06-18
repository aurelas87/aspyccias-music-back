<?php

namespace App\Entity;

use App\Repository\ProfileRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfileRepository::class)]
class Profile
{
    const DEFAULT_NAME = 'Aspyccias';

    #[ORM\Id]
    #[ORM\Column(length: 9)]
    private ?string $name = self::DEFAULT_NAME;

    #[ORM\Column(length: 255)]
    private ?string $welcome = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getWelcome(): ?string
    {
        return $this->welcome;
    }

    public function setWelcome(string $welcome): static
    {
        $this->welcome = $welcome;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
