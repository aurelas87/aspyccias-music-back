<?php

namespace App\Entity\Release;

use App\Model\Release\ReleaseLinkType;
use App\Repository\Release\ReleaseLinkRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;

#[ORM\Entity(repositoryClass: ReleaseLinkRepository::class)]
class ReleaseLink
{
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'links')]
    #[ORM\JoinColumn(nullable: false)]
    #[Ignore]
    private ?Release $release = null;

    #[ORM\Id]
    #[ORM\Column(type: Types::SMALLINT, enumType: ReleaseLinkType::class)]
    #[Groups('details')]
    private ?ReleaseLinkType $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('details')]
    private ?string $link = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups('details')]
    private ?string $embedded = null;

    public function getRelease(): ?Release
    {
        return $this->release;
    }

    public function setRelease(?Release $release): static
    {
        $this->release = $release;

        return $this;
    }

    public function getType(): ?ReleaseLinkType
    {
        return $this->type;
    }

    public function setType(ReleaseLinkType $type): static
    {
        $this->type = $type;

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

    public function getEmbedded(): ?string
    {
        return $this->embedded;
    }

    public function setEmbedded(?string $embedded): static
    {
        $this->embedded = $embedded;

        return $this;
    }
}
