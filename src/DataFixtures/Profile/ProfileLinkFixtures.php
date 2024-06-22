<?php

namespace App\DataFixtures\Profile;

use App\Entity\Profile\ProfileLink;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * @codeCoverageIgnore
 */
class ProfileLinkFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $facebookLink = new ProfileLink();
        $facebookLink->setName('facebook');
        $facebookLink->setLink('https://www.facebook.com');
        $facebookLink->setPosition(1);

        $instagramLink = new ProfileLink();
        $instagramLink->setName('instagram');
        $instagramLink->setLink('https://www.instagram.com');
        $instagramLink->setPosition(2);

        $youtubeLink = new ProfileLink();
        $youtubeLink->setName('youtube');
        $youtubeLink->setLink('https://www.youtube.com');
        $youtubeLink->setPosition(3);

        // Persist not in order to test the order by
        $manager->persist($youtubeLink);
        $manager->persist($facebookLink);
        $manager->persist($instagramLink);

        $manager->flush();
    }
}
