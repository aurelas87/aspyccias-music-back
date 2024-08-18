<?php

namespace App\Entity\Release;

use App\Repository\Release\ReleaseCreditTypeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;

#[ORM\Entity(repositoryClass: ReleaseCreditTypeRepository::class)]
class ReleaseCreditTypeTranslation
{

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'translations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Ignore]
    private ?ReleaseCreditType $releaseCreditType = null;

    #[ORM\Id]
    #[ORM\Column(length: 2)]
    #[Ignore]
    private ?string $locale = null;

    #[ORM\Column(length: 255)]
    #[Groups('details')]
    private ?string $creditName = null;

    public function getReleaseCreditType(): ?ReleaseCreditType
    {
        return $this->releaseCreditType;
    }

    public function setReleaseCreditType(?ReleaseCreditType $releaseCreditType): static
    {
        $this->releaseCreditType = $releaseCreditType;

        return $this;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): ReleaseCreditTypeTranslation
    {
        $this->locale = $locale;

        return $this;
    }

    public function getCreditName(): ?string
    {
        return $this->creditName;
    }

    public function setCreditName(string $creditName): static
    {
        $this->creditName = $creditName;

        return $this;
    }
}
