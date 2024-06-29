<?php

namespace App\Entity\News;

use App\Repository\News\NewsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: NewsRepository::class)]
class News
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('list')]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups('default')]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 30)]
    #[Groups('default')]
    private ?string $preview_image = null;

    /**
     * @var Collection<int, NewsTranslation>
     */
    #[ORM\OneToMany(
        targetEntity: NewsTranslation::class,
        mappedBy: 'news',
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    #[Groups(['default'])]
    private Collection $translations;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getPreviewImage(): ?string
    {
        return $this->preview_image;
    }

    public function setPreviewImage(string $preview_image): static
    {
        $this->preview_image = $preview_image;

        return $this;
    }

    /**
     * @return Collection<int, NewsTranslation>
     */
    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function addTranslation(NewsTranslation $translation): static
    {
        if (!$this->translations->contains($translation)) {
            $this->translations->add($translation);
            $translation->setNews($this);
        }

        return $this;
    }

    public function removeTranslation(NewsTranslation $translation): static
    {
        if ($this->translations->removeElement($translation)) {
            // set the owning side to null (unless already changed)
            if ($translation->getNews() === $this) {
                $translation->setNews(null);
            }
        }

        return $this;
    }
}
