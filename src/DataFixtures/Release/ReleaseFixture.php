<?php

namespace App\DataFixtures\Release;

use App\Entity\Release\Release;
use App\Entity\Release\ReleaseCredit;
use App\Entity\Release\ReleaseLink;
use App\Entity\Release\ReleaseTrack;
use App\Entity\Release\ReleaseTranslation;
use App\Model\Release\ReleaseLinkCategory;
use App\Model\Release\ReleaseType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class ReleaseFixture extends Fixture implements DependentFixtureInterface
{
    public const TOTAL_SINGLES = 4;
    public const TOTAL_EPS = 3;
    public const TOTAL_ALBUMS = 2;
    public const TOTAL_RELEASES = self::TOTAL_SINGLES + self::TOTAL_EPS + self::TOTAL_ALBUMS;
    public const START_DATE = '2023-01-01T00:00:00Z';
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    private function addCreditsToRelease(Release $release): Release
    {
        $release->addCredit(
            (new ReleaseCredit())
                ->setReleaseCreditType($this->getReference(ReleaseCreditTypeFixtures::COMPOSER_CREDIT_TYPE))
                ->setFullName('John Composer')
                ->setLink('https://www.aspyccias-music.com')
        );

        $release->addCredit(
            (new ReleaseCredit())
                ->setReleaseCreditType($this->getReference(ReleaseCreditTypeFixtures::PRODUCER_CREDIT_TYPE))
                ->setFullName('John Producer')
        );

        $release->addCredit(
            (new ReleaseCredit())
                ->setReleaseCreditType($this->getReference(ReleaseCreditTypeFixtures::LYRICIST_CREDIT_TYPE))
                ->setFullName('John Lyricist')
        );

        $release->addCredit(
            (new ReleaseCredit())
                ->setReleaseCreditType($this->getReference(ReleaseCreditTypeFixtures::EDITOR_CREDIT_TYPE))
                ->setFullName('John Editor')
        );

        $release->addCredit(
            (new ReleaseCredit())
                ->setReleaseCreditType($this->getReference(ReleaseCreditTypeFixtures::VIOLINIST_CREDIT_TYPE))
                ->setFullName('John Violinist')
        );

        $release->addCredit(
            (new ReleaseCredit())
                ->setReleaseCreditType($this->getReference(ReleaseCreditTypeFixtures::VOICE_CREDIT_TYPE))
                ->setFullName('John Voice')
        );

        return $release;
    }

    private function addLinksToRelease(Release $release): Release
    {
        $release->addLink(
            (new ReleaseLink())
                ->setReleaseLinkName($this->getReference(ReleaseLinkNameFixtures::YOUTUBE_LINK_NAME))
                ->setCategory(ReleaseLinkCategory::listen)
                ->setLink('https://www.youtube.com/watch?v=kQWUmBwZCKY')
        );

        $release->addLink(
            (new ReleaseLink())
                ->setReleaseLinkName($this->getReference(ReleaseLinkNameFixtures::SPOTIFY_LINK_NAME))
                ->setCategory(ReleaseLinkCategory::listen)
                ->setLink('https://www.spotify.com')
        );

        $release->addLink(
            (new ReleaseLink())
                ->setReleaseLinkName($this->getReference(ReleaseLinkNameFixtures::DEEZER_LINK_NAME))
                ->setCategory(ReleaseLinkCategory::listen)
                ->setLink('https://www.deezer.com')
        );

        $release->addLink(
            (new ReleaseLink())
                ->setReleaseLinkName($this->getReference(ReleaseLinkNameFixtures::BANDCAMP_LINK_NAME))
                ->setCategory(ReleaseLinkCategory::listen)
                ->setLink('https://www.bandcamp.com')
        );

        $release->addLink(
            (new ReleaseLink())
                ->setReleaseLinkName($this->getReference(ReleaseLinkNameFixtures::BANDCAMP_LINK_NAME))
                ->setCategory(ReleaseLinkCategory::buy)
                ->setLink('https://aspyccias.bandcamp.com/track/in-a-spaceship')
        );

        $release->addLink(
            (new ReleaseLink())
                ->setReleaseLinkName($this->getReference(ReleaseLinkNameFixtures::APPLE_LINK_NAME))
                ->setCategory(ReleaseLinkCategory::buy)
                ->setLink('https://music.apple.com')
        );

        $release->addLink(
            (new ReleaseLink())
                ->setReleaseLinkName($this->getReference(ReleaseLinkNameFixtures::AMAZON_LINK_NAME))
                ->setCategory(ReleaseLinkCategory::buy)
                ->setLink('https://www.amazon.com')
        );

        $release->addLink(
            (new ReleaseLink())
                ->setReleaseLinkName($this->getReference(ReleaseLinkNameFixtures::ODESLI_LINK_NAME))
                ->setCategory(ReleaseLinkCategory::smart_link)
                ->setEmbedded('<div style="max-width:100%;">'.
                    '<div style="position:relative;padding-bottom:calc(56.25% + 52px);height: 0;">'.
                    '<iframe style="position:absolute;top:0;left:0;" width="100%" height="100%" '.
                    'src="https://odesli.co/embed/?url=https%3A%2F%2Fsong.link%2Fcxdvh54nmjqct&theme=light" '.
                    'frameborder="0" allowfullscreen sandbox="allow-same-origin allow-scripts allow-presentation '.
                    'allow-popups allow-popups-to-escape-sandbox" allow="clipboard-read; clipboard-write"></iframe>'.
                    '</div></div>')
        );

        return $release;
    }

    private function addTracksToRelease(Release $release, int $indexRelease): Release
    {
        switch ($release->getType()) {
            case ReleaseType::single:
            default:
                $nbTracks = 1;
                break;
            case ReleaseType::ep:
                $nbTracks = 2;
                break;
            case ReleaseType::album:
                $nbTracks = 10;
                break;
        }

        $duration = 150; //2min30 and add 1sec to next tracks

        for ($indexTrack = 1; $indexTrack <= $nbTracks; $indexTrack++) {
            $strTrackPosition = \str_pad($indexTrack, 2, '0', STR_PAD_LEFT);

            $release->addTrack(
                (new ReleaseTrack())
                    ->setTitle("Release Track $indexRelease-$strTrackPosition")
                    ->setPosition($indexTrack)
                    ->setDuration($indexTrack > 1 ? ++$duration : $duration)
            );
        }

        return $release;
    }

    private function createRelease(
        int $indexRelease,
        ReleaseType $releaseType,
        \DateTimeImmutable $releaseDate
    ): Release {
        $releaseTitle = "Release Title $indexRelease";
        $releaseSlug = $this->slugger->slug($releaseTitle)->lower();

        $release = new Release();
        $release->setSlug($releaseSlug)
            ->setType($releaseType)
            ->setReleaseDate($releaseDate)
            ->setTitle($releaseTitle)
            ->setArtworkFrontImage("$releaseSlug-front-cover")
            ->setArtworkBackImage("$releaseSlug-back-cover")
            ->addTranslation(
                (new ReleaseTranslation())
                    ->setLocale('fr')
                    ->setDescription("Description de la sortie $indexRelease")
            )
            ->addTranslation(
                (new ReleaseTranslation())
                    ->setLocale('en')
                    ->setDescription("Release description $indexRelease")
            );

        $release = $this->addCreditsToRelease($release);
        $release = $this->addLinksToRelease($release);
        return $this->addTracksToRelease($release, $indexRelease);
    }

    public function load(ObjectManager $manager): void
    {
        $releaseDate = new \DateTimeImmutable(self::START_DATE);
        $releaseType = ReleaseType::single;

        for ($indexRelease = 1; $indexRelease <= self::TOTAL_RELEASES; $indexRelease++) {
            if ($indexRelease > 1) {
                $releaseDate = $releaseDate->add(new \DateInterval('P1M'));
            }

            if ($indexRelease > self::TOTAL_SINGLES) {
                $releaseType = ReleaseType::ep;
            }

            if ($indexRelease > (self::TOTAL_SINGLES + self::TOTAL_EPS)) {
                $releaseType = ReleaseType::album;
            }

            $manager->persist($this->createRelease($indexRelease, $releaseType, $releaseDate));
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ReleaseCreditTypeFixtures::class,
        ];
    }
}
