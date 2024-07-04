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
        $facebookLink->setName('facebook')
            ->setLink('https://www.facebook.com')
            ->setPosition(1);

        $instagramLink = new ProfileLink();
        $instagramLink->setName('instagram')
            ->setLink('https://www.instagram.com')
            ->setPosition(2);

        $youtubeLink = new ProfileLink();
        $youtubeLink->setName('youtube')
            ->setLink('https://www.youtube.com')
            ->setPosition(3);

        // Persist not in order to test the order by
        $manager->persist($youtubeLink);
        $manager->persist($facebookLink);
        $manager->persist($instagramLink);

        $manager->flush();
    }
}
