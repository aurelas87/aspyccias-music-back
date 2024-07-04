<?php

namespace App\Entity\Release;

use App\Repository\Release\ReleaseTrackRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;

#[ORM\Entity(repositoryClass: ReleaseTrackRepository::class)]
class ReleaseTrack
{
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'tracks')]
    #[ORM\JoinColumn(nullable: false)]
    #[Ignore]
    private ?Release $release = null;

    #[ORM\Id]
    #[ORM\Column(length: 255)]
    #[Groups('details')]
    private ?string $title = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups('details')]
    private ?int $position = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups('details')]
    private ?int $duration = null;

    public function getRelease(): ?Release
    {
        return $this->release;
    }

    public function setRelease(?Release $release): static
    {
        $this->release = $release;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }
}
