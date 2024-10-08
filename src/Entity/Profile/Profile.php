<?php

namespace App\Entity\Profile;

use App\Repository\Profile\ProfileRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Ignore;

#[ORM\Entity(repositoryClass: ProfileRepository::class)]
class Profile
{
    #[ORM\Id]
    #[ORM\Column(length: 2)]
    #[Ignore]
    private ?string $locale = null;

    #[ORM\Column(length: 255)]
    private ?string $welcome = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): static
    {
        $this->locale = $locale;

        return $this;
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
