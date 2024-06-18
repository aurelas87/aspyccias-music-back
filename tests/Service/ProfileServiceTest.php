<?php

namespace App\Tests\Service;

use App\Entity\Profile;
use App\Exception\Profile\ProfileNotFoundException;
use App\Service\ProfileService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProfileServiceTest extends KernelTestCase
{
    private ProfileService $profileService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->profileService = static::getContainer()->get(ProfileService::class);
    }

    public function testGetAspycciasProfile(): void
    {
        $profile = $this->profileService->getAspycciasProfile();

        static::assertEquals(Profile::DEFAULT_NAME, $profile->getName());
        static::assertEquals('Bienvenue', $profile->getWelcome());
        static::assertEquals('Je me présente', $profile->getDescription());
    }

    public function testGetUnknownProfile(): void
    {
        $profile = $this->profileService->getAspycciasProfile();
        $manager = static::getContainer()->get('doctrine')->getManager();
        $manager->remove($profile);
        $manager->flush();

        try {
            $this->profileService->getAspycciasProfile();
        } catch (ProfileNotFoundException $e) {
            static::assertEquals('Profile with name "'.Profile::DEFAULT_NAME.'" not found', $e->getMessage());
        }
    }
}
