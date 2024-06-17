<?php

namespace App\Entity;

use App\Repository\ReleaseLinkRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReleaseLinkRepository::class)]
class ReleaseLink
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Release::class, inversedBy: 'links')]
    private ?Release $release = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $link = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $embedded = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRelease(): ?Release
    {
        return $this->release;
    }

    public function setRelease(?Release $release): void
    {
        $this->release = $release;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): static
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
