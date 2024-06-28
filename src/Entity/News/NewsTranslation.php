<?php

namespace App\Entity\News;

use App\Repository\News\NewsTranslationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;

#[ORM\Entity(repositoryClass: NewsTranslationRepository::class)]
#[ORM\UniqueConstraint(fields: ['news', 'locale'])]
class NewsTranslation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Ignore]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'translations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Ignore]
    private ?News $news = null;

    #[ORM\Column(length: 2)]
    #[Ignore]
    private ?string $locale = null;

    #[ORM\Column(length: 255)]
    #[Groups('list')]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups('details')]
    private ?string $content = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNews(): ?News
    {
        return $this->news;
    }

    public function setNews(?News $news): static
    {
        $this->news = $news;

        return $this;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): static
    {
        $this->locale = $locale;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }
}
