<?php

namespace App\DataFixtures;

use App\Entity\Profile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProfileFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $profile = new Profile();
        $profile->setWelcome('Bienvenue');
        $profile->setDescription('Je me présente');

        $manager->persist($profile);
        $manager->flush();
    }
}
