<?php

namespace App\DataFixtures\Profile;

use App\Entity\Profile\Profile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * @codeCoverageIgnore
 */
class ProfileFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $profileFR = new Profile();
        $profileFR->setLocale('fr')
            ->setWelcome('Bienvenue')
            ->setDescription('Je me présente');

        $profileEN = new Profile();
        $profileEN->setLocale('en')
            ->setWelcome('Welcome')
            ->setDescription('Let me introduce myself');

        $manager->persist($profileFR);
        $manager->persist($profileEN);

        $manager->flush();
    }
}
