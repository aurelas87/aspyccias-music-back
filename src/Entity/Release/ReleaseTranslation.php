<?php

namespace App\Entity\Release;

use App\Repository\Release\ReleaseTranslationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;

#[ORM\Entity(repositoryClass: ReleaseTranslationRepository::class)]
class ReleaseTranslation
{
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'translations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Ignore]
    private ?Release $release = null;

    #[ORM\Id]
    #[ORM\Column(length: 2)]
    #[Ignore]
    private ?string $locale = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups('details')]
    private ?string $description = null;

    public function getRelease(): ?Release
    {
        return $this->release;
    }

    public function setRelease(?Release $release): static
    {
        $this->release = $release;

        return $this;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): static
    {
        $this->locale = $locale;

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
