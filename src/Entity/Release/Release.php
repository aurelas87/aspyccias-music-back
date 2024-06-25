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

    #[ORM\OneToMany(targetEntity: ReleaseTranslation::class, mappedBy: 'release')]
    private Collection $translations;

    #[ORM\OneToMany(targetEntity: ReleaseLink::class, mappedBy: 'release')]
    private Collection $links;

    #[ORM\OneToMany(targetEntity: ReleaseCredit::class, mappedBy: 'release')]
    private Collection $credits;

    #[ORM\OneToMany(targetEntity: ReleaseTrack::class, mappedBy: 'release')]
    private Collection $tracks;

    public function __construct()
    {
        $this->links = new ArrayCollection();
        $this->credits = new ArrayCollection();
        $this->tracks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function setTranslations(Collection $translations): static
    {
        $this->translations = $translations;

        return $this;
    }

    public function getLinks(): Collection
    {
        return $this->links;
    }

    public function setLinks(Collection $links): static
    {
        $this->links = $links;

        return $this;
    }

    public function getCredits(): Collection
    {
        return $this->credits;
    }

    public function setCredits(Collection $credits): static
    {
        $this->credits = $credits;

        return $this;
    }

    public function getTracks(): Collection
    {
        return $this->tracks;
    }

    public function setTracks(Collection $tracks): static
    {
        $this->tracks = $tracks;

        return $this;
    }
}
