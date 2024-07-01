<?php

namespace App\Entity\Release;

use App\Repository\Release\ReleaseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReleaseRepository::class)]
#[ORM\Table(name: '`release`')]
class Release
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $type = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $release_date = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descriptionFR = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descriptionEN = null;

    #[ORM\Column(length: 255)]
    private ?string $artwork_front_image = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $artwork_back_image = null;

    /**
     * @var Collection<int, ReleaseTranslation>
     */
    #[ORM\OneToMany(
        targetEntity: ReleaseTranslation::class,
        mappedBy: 'releaseInstance',
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $translations;

    /**
     * @var Collection<int, ReleaseCredit>
     */
    #[ORM\OneToMany(
        targetEntity: ReleaseCredit::class,
        mappedBy: 'releaseInstance',
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $credits;

    /**
     * @var Collection<int, ReleaseLink>
     */
    #[ORM\OneToMany(
        targetEntity: ReleaseLink::class,
        mappedBy: 'releaseInstance',
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $links;

    /**
     * @var Collection<int, ReleaseTrack>
     */
    #[ORM\OneToMany(
        targetEntity: ReleaseTrack::class,
        mappedBy: 'releaseInstance',
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $tracks;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
        $this->credits = new ArrayCollection();
        $this->links = new ArrayCollection();
        $this->tracks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

        return $this;
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

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->release_date;
    }

    public function setReleaseDate(\DateTimeInterface $release_date): static
    {
        $this->release_date = $release_date;

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

    public function getDescriptionFR(): ?string
    {
        return $this->descriptionFR;
    }

    public function setDescriptionFR(?string $descriptionFR): static
    {
        $this->descriptionFR = $descriptionFR;

        return $this;
    }

    public function getDescriptionEN(): ?string
    {
        return $this->descriptionEN;
    }

    public function setDescriptionEN(?string $descriptionEN): static
    {
        $this->descriptionEN = $descriptionEN;

        return $this;
    }

    public function getArtworkFrontImage(): ?string
    {
        return $this->artwork_front_image;
    }

    public function setArtworkFrontImage(string $artwork_front_image): static
    {
        $this->artwork_front_image = $artwork_front_image;

        return $this;
    }

    public function getArtworkBackImage(): ?string
    {
        return $this->artwork_back_image;
    }

    public function setArtworkBackImage(?string $artwork_back_image): static
    {
        $this->artwork_back_image = $artwork_back_image;

        return $this;
    }

    /**
     * @return Collection<int, ReleaseTranslation>
     */
    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function addTranslation(ReleaseTranslation $translation): static
    {
        if (!$this->translations->contains($translation)) {
            $this->translations->add($translation);
            $translation->setRelease($this);
        }

        return $this;
    }

    public function removeTranslation(ReleaseTranslation $translation): static
    {
        if ($this->translations->removeElement($translation)) {
            // set the owning side to null (unless already changed)
            if ($translation->getRelease() === $this) {
                $translation->setRelease(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ReleaseCredit>
     */
    public function getCredits(): Collection
    {
        return $this->credits;
    }

    public function addCredit(ReleaseCredit $credit): static
    {
        if (!$this->credits->contains($credit)) {
            $this->credits->add($credit);
            $credit->setRelease($this);
        }

        return $this;
    }

    public function removeCredit(ReleaseCredit $credit): static
    {
        if ($this->credits->removeElement($credit)) {
            // set the owning side to null (unless already changed)
            if ($credit->getRelease() === $this) {
                $credit->setRelease(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ReleaseLink>
     */
    public function getLinks(): Collection
    {
        return $this->links;
    }

    public function addLink(ReleaseLink $link): static
    {
        if (!$this->links->contains($link)) {
            $this->links->add($link);
            $link->setRelease($this);
        }

        return $this;
    }

    public function removeLink(ReleaseLink $link): static
    {
        if ($this->links->removeElement($link)) {
            // set the owning side to null (unless already changed)
            if ($link->getRelease() === $this) {
                $link->setRelease(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ReleaseTrack>
     */
    public function getTracks(): Collection
    {
        return $this->tracks;
    }

    public function addTrack(ReleaseTrack $track): static
    {
        if (!$this->tracks->contains($track)) {
            $this->tracks->add($track);
            $track->setRelease($this);
        }

        return $this;
    }

    public function removeTrack(ReleaseTrack $track): static
    {
        if ($this->tracks->removeElement($track)) {
            // set the owning side to null (unless already changed)
            if ($track->getRelease() === $this) {
                $track->setRelease(null);
            }
        }

        return $this;
    }
}
