<?php

namespace App\Entity\Release;

use App\Model\Release\ReleaseType;
use App\Repository\Release\ReleaseRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;

#[ORM\Entity(repositoryClass: ReleaseRepository::class)]
#[ORM\Table(name: '`release`')]
class Release
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Ignore]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups('default')]
    private ?string $slug = null;

    #[ORM\Column(type: Types::SMALLINT, enumType: ReleaseType::class)]
    #[Groups('admin')]
    private ?ReleaseType $type = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Groups('default')]
    private ?DateTimeInterface $releaseDate = null;

    #[ORM\Column(length: 255)]
    #[Groups(['default', 'admin-tracks', 'admin-credits', 'admin-links'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    #[Groups('details')]
    private ?bool $artworkBackImage = false;

    /**
     * @var Collection<int, ReleaseTranslation>
     */
    #[ORM\OneToMany(
        targetEntity: ReleaseTranslation::class,
        mappedBy: 'release',
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    #[Groups('details')]
    private Collection $translations;

    /**
     * @var Collection<int, ReleaseCredit>
     */
    #[ORM\OneToMany(
        targetEntity: ReleaseCredit::class,
        mappedBy: 'release',
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    #[Groups(['details', 'admin-credits'])]
    private Collection $credits;

    /**
     * @var Collection<int, ReleaseLink>
     */
    #[ORM\OneToMany(
        targetEntity: ReleaseLink::class,
        mappedBy: 'release',
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    #[Groups(['details', 'admin-links'])]
    private Collection $links;

    /**
     * @var Collection<int, ReleaseTrack>
     */
    #[ORM\OneToMany(
        targetEntity: ReleaseTrack::class,
        mappedBy: 'release',
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    #[ORM\OrderBy(['position' => 'ASC'])]
    #[Groups(['details', 'admin-tracks'])]
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

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getType(): ?ReleaseType
    {
        return $this->type;
    }

    public function setType(ReleaseType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getReleaseDate(): ?DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(DateTimeInterface $releaseDate): static
    {
        $this->releaseDate = $releaseDate;

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

    public function getArtworkBackImage(): ?bool
    {
        return $this->artworkBackImage;
    }

    public function setArtworkBackImage(bool $artworkBackImage): static
    {
        $this->artworkBackImage = $artworkBackImage;

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
        if ($this->translations->removeElement($translation) && $translation->getRelease() === $this) {
            // set the owning side to null (unless already changed)
            $translation->setRelease(null);
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
        if ($this->credits->removeElement($credit) && $credit->getRelease() === $this) {
            // set the owning side to null (unless already changed)
            $credit->setRelease(null);
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
        if ($this->links->removeElement($link) && $link->getRelease() === $this) {
            // set the owning side to null (unless already changed)
            $link->setRelease(null);
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
        if ($this->tracks->removeElement($track) && $track->getRelease() === $this) {
            // set the owning side to null (unless already changed)
            $track->setRelease(null);
        }

        return $this;
    }
}
