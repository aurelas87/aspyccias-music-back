<?php

namespace App\Entity\Release;

use App\Repository\Release\ReleaseCreditRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;

#[ORM\Entity(repositoryClass: ReleaseCreditRepository::class)]
class ReleaseCredit
{
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'credits')]
    #[ORM\JoinColumn(nullable: false)]
    #[Ignore]
    private ?Release $release = null;

    #[ORM\Id]
    #[ORM\ManyToOne]
    #[Groups('details')]
    private ?ReleaseCreditType $releaseCreditType = null;

    #[ORM\Column(length: 255)]
    #[Groups('details')]
    private ?string $fullName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('details')]
    private ?string $link = null;

    public function getRelease(): ?Release
    {
        return $this->release;
    }

    public function setRelease(?Release $release): static
    {
        $this->release = $release;

        return $this;
    }

    public function getReleaseCreditType(): ?ReleaseCreditType
    {
        return $this->releaseCreditType;
    }

    public function setReleaseCreditType(ReleaseCreditType $releaseCreditType): static
    {
        $this->releaseCreditType = $releaseCreditType;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): static
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): static
    {
        $this->link = $link;

        return $this;
    }
}
