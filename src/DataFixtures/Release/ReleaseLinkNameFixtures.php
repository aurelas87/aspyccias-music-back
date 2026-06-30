<?php

namespace App\DataFixtures\Release;

use App\Entity\Release\ReleaseLinkName;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ReleaseLinkNameFixtures extends Fixture
{
    public const string YOUTUBE_LINK_NAME = 'youtube';
    public const string SPOTIFY_LINK_NAME = 'spotify';
    public const string DEEZER_LINK_NAME = 'deezer';
    public const string BANDCAMP_LINK_NAME = 'bandcamp';
    public const string APPLE_LINK_NAME = 'apple';
    public const string AMAZON_LINK_NAME = 'amazon';
    public const string ODESLI_LINK_NAME = 'odesli';

    public function load(ObjectManager $manager): void
    {
        $youtubeLinkName = new ReleaseLinkName();
        $youtubeLinkName->setLinkName(self::YOUTUBE_LINK_NAME);

        $spotifyLinkname = new ReleaseLinkName();
        $spotifyLinkname->setLinkName(self::SPOTIFY_LINK_NAME);

        $deezerLinkName = new ReleaseLinkName();
        $deezerLinkName->setLinkName(self::DEEZER_LINK_NAME);

        $bandcampLinkName = new ReleaseLinkName();
        $bandcampLinkName->setLinkName(self::BANDCAMP_LINK_NAME);

        $appleLinkName = new ReleaseLinkName();
        $appleLinkName->setLinkName(self::APPLE_LINK_NAME);

        $amazonLinkName = new ReleaseLinkName();
        $amazonLinkName->setLinkName(self::AMAZON_LINK_NAME);

        $odesliLinkname = new ReleaseLinkName();
        $odesliLinkname->setLinkName(self::ODESLI_LINK_NAME);

        $manager->persist($youtubeLinkName);
        $manager->persist($spotifyLinkname);
        $manager->persist($deezerLinkName);
        $manager->persist($bandcampLinkName);
        $manager->persist($appleLinkName);
        $manager->persist($amazonLinkName);
        $manager->persist($odesliLinkname);

        $manager->flush();

        $this->addReference(self::YOUTUBE_LINK_NAME, $youtubeLinkName);
        $this->addReference(self::SPOTIFY_LINK_NAME, $spotifyLinkname);
        $this->addReference(self::DEEZER_LINK_NAME, $deezerLinkName);
        $this->addReference(self::BANDCAMP_LINK_NAME, $bandcampLinkName);
        $this->addReference(self::APPLE_LINK_NAME, $appleLinkName);
        $this->addReference(self::AMAZON_LINK_NAME, $amazonLinkName);
        $this->addReference(self::ODESLI_LINK_NAME, $odesliLinkname);
    }
}
