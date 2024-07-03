<?php

namespace App\Entity\Release;

use App\Repository\Release\ReleaseCreditTypeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;

#[ORM\Entity(repositoryClass: ReleaseCreditTypeRepository::class)]
class ReleaseCreditType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Ignore]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups('details')]
    private ?string $creditName = null;

    public function getId(): ?int
    {
        return $this->id;
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
