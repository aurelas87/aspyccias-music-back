<?php

namespace App\DataFixtures\News;

use App\Entity\News\News;
use App\Entity\News\NewsTranslation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class NewsFixtures extends Fixture
{
    public const TOTAL_NEWS = 13;
    public const START_DATE = '2024-06-01T00:00:0Z';
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    /**
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        $newsDate = new \DateTimeImmutable(self::START_DATE);

        for ($indexNews = 1; $indexNews <= self::TOTAL_NEWS; $indexNews++) {
            if ($indexNews > 1) {
                $newsDate = $newsDate->add(new \DateInterval('P1D'));
            }

            $englishNewsTitle = "News Title $indexNews";

            $news = new News();
            $news->setDate($newsDate)
                ->setSlug($this->slugger->slug($newsDate->format('Y-m-d').'-'.$englishNewsTitle)->lower())
                ->setPreviewImage("preview-news-$indexNews")
                ->addTranslation(
                    (new NewsTranslation())
                        ->setLocale('fr')
                        ->setTitle("Titre de l'actualité $indexNews")
                        ->setContent("Contenu de l'actualité $indexNews")
                )
                ->addTranslation(
                    (new NewsTranslation())
                        ->setLocale('en')
                        ->setTitle($englishNewsTitle)
                        ->setContent("News Content $indexNews")
                );

            $manager->persist($news);
        }

        $manager->flush();
    }
}
