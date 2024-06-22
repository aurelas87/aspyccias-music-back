<?php

namespace App\Tests\Controller\Profile;

use App\Exception\Profile\ProfileNotFoundException;
use App\Service\Profile\ProfileService;
use App\Tests\Controller\JsonResponseTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\Translator;

class ProfileControllerTest extends JsonResponseTestCase
{
    protected ProfileService $profileService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->profileService = static::getContainer()->get(ProfileService::class);
    }

    public function testGetProfileInEN(): void
    {
        // Default language if no ACCEPT_LANGUAGE header sent
        $this->client->request('GET', '/profile');
        $expectedProfile = $this->profileService->getProfile('en');
        $this->assertJsonResponse($expectedProfile);

        // Default language if ACCEPT_LANGUAGE header sent with unsupported language
        $this->client->request('GET', '/profile', [], [], ['HTTP_ACCEPT_LANGUAGE' => 'de']);
        $this->assertJsonResponse($expectedProfile);
    }

    public function testGetProfileInFR(): void
    {
        $this->client->request('GET', '/profile', [], [], ['HTTP_ACCEPT_LANGUAGE' => 'fr']);

        $expectedProfile = $this->profileService->getProfile('fr');
        $this->assertJsonResponse($expectedProfile);
    }

    public function testGetProfileIfNotFound(): void
    {
        // Remove profile in "fr" version
        $profile = $this->profileService->getProfile('fr');
        $manager = static::getContainer()->get('doctrine')->getManager();
        $manager->remove($profile);
        $manager->flush();

        $this->client->request('GET', '/profile', [], [], ['HTTP_ACCEPT_LANGUAGE' => 'fr']);

        $expectedException = new ProfileNotFoundException();

        /** @var Translator $translator */
        $translator = static::getContainer()->get('translator');
        $translator->setLocale('fr');

        $this->assertJsonResponse(
            [
                'code' => $expectedException->getStatusCode(),
                'message' => $translator->trans($expectedException->getMessage()),
            ],
            Response::HTTP_NOT_FOUND
        );
    }
}
