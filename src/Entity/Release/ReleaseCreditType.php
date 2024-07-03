<?php

namespace App\Entity\Release;

use App\Repository\Release\ReleaseCreditTypeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReleaseCreditTypeRepository::class)]
class ReleaseCreditType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
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
