<?php

namespace App\DataFixtures\Profile;

use App\Entity\Profile\Profile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProfileFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $profileFR = new Profile();
        $profileFR->setLocale('fr');
        $profileFR->setWelcome('Bienvenue');
        $profileFR->setDescription('Je me présente');

        $profileEN = new Profile();
        $profileEN->setLocale('en');
        $profileEN->setWelcome('Welcome');
        $profileEN->setDescription('Let me introduce myself');

        $manager->persist($profileFR);
        $manager->persist($profileEN);

        $manager->flush();
    }
}
