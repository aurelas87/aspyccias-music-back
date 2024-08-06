<?php

namespace App\Entity\Release;

use App\Repository\Release\ReleaseLinkNameRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;

#[ORM\Entity(repositoryClass: ReleaseLinkNameRepository::class)]
class ReleaseLinkName
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Ignore]
    private ?int $id = null;

    #[ORM\Column(length: 20, unique: true)]
    #[Groups('details')]
    private ?string $linkName = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLinkName(): ?string
    {
        return $this->linkName;
    }

    public function setLinkName(string $linkName): static
    {
        $this->linkName = $linkName;

        return $this;
    }
}
