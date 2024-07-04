<?php

namespace App\Tests\Service\Profile;

use App\Exception\Profile\ProfileNotFoundException;
use App\Service\Profile\ProfileService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProfileServiceTest extends KernelTestCase
{
    private ProfileService $profileService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->profileService = $this->getContainer()->get(ProfileService::class);
    }

    public function testGetProfileInEN(): void
    {
        $profile = $this->profileService->getProfile('en');

        static::assertSame('Welcome', $profile->getWelcome());
        static::assertSame('Let me introduce myself', $profile->getDescription());
    }

    public function testGetProfileInFR(): void
    {
        $profile = $this->profileService->getProfile('fr');

        static::assertSame('Bienvenue', $profile->getWelcome());
        static::assertSame('Je me présente', $profile->getDescription());
    }

    public function testGetProfileThrowsExceptionIfLanguageVersionNotFound(): void
    {
        // Language "de" not enabled
        try {
            $this->profileService->getProfile('de');
        } catch (ProfileNotFoundException $e) {
            static::assertSame('errors.profile.not_found', $e->getMessage());
        }

        // Remove profile in "fr" version
        $profile = $this->profileService->getProfile('fr');
        $manager = $this->getContainer()->get('doctrine')->getManager();
        $manager->remove($profile);
        $manager->flush();

        try {
            $this->profileService->getProfile('fr');
        } catch (ProfileNotFoundException $e) {
            static::assertSame('errors.profile.not_found', $e->getMessage());
        }
    }
}
