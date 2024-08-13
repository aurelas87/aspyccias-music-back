<?php

namespace App\Entity\Release;

use App\Repository\Release\ReleaseCreditTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    private ?string $creditNameKey = null;

    /**
     * @var Collection<int, ReleaseCreditTypeTranslation>
     */
    #[ORM\OneToMany(
        targetEntity: ReleaseCreditTypeTranslation::class,
        mappedBy: 'releaseCreditType',
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    #[Groups('details')]
    private Collection $translations;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreditNameKey(): ?string
    {
        return $this->creditNameKey;
    }

    public function setCreditNameKey(string $creditNameKey): static
    {
        $this->creditNameKey = $creditNameKey;

        return $this;
    }

    /**
     * @return Collection<int, ReleaseCreditTypeTranslation>
     */
    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function addTranslation(ReleaseCreditTypeTranslation $translation): static
    {
        if (!$this->translations->contains($translation)) {
            $this->translations->add($translation);
            $translation->setReleaseCreditType($this);
        }

        return $this;
    }

    public function removeTranslation(ReleaseCreditTypeTranslation $translation): static
    {
        if ($this->translations->removeElement($translation)) {
            // set the owning side to null (unless already changed)
            if ($translation->getReleaseCreditType() === $this) {
                $translation->setReleaseCreditType(null);
            }
        }

        return $this;
    }
}
