<?php

namespace App\DataFixtures\News;

use App\Entity\News\News;
use App\Entity\News\NewsTranslation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class NewsFixtures extends Fixture
{
    public const TOTAL_NEWS = 13;
    public const START_DATE = '2024-06-01T10:15:30Z';

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

            $news = new News();
            $news->setDate($newsDate);
            $news->setPreviewImage("preview-news-$indexNews");

            $news->addTranslation(
                (new NewsTranslation())
                    ->setLocale('fr')
                    ->setTitle("Titre de l'actualité $indexNews")
                    ->setContent("Contenu de l'actualité $indexNews")
            );

            $news->addTranslation(
                (new NewsTranslation())
                    ->setLocale('en')
                    ->setTitle("News Title $indexNews")
                    ->setContent("News Content $indexNews")
            );

            $manager->persist($news);
        }

        $manager->flush();
    }
}
