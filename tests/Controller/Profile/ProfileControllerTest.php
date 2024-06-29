<?php

namespace App\Tests\Controller\Profile;

use App\Exception\Profile\ProfileNotFoundException;
use App\Service\Profile\ProfileService;
use App\Tests\Controller\JsonResponseTestCase;
use Symfony\Component\HttpFoundation\Response;

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
        $this->serializerAndAssertJsonResponse($expectedProfile);

        // en specifically given in the request header
        $this->client->request(method: 'GET', uri: '/profile', server: ['HTTP_ACCEPT_LANGUAGE' => 'en']);
        $this->serializerAndAssertJsonResponse($expectedProfile);

        // Default language if ACCEPT_LANGUAGE header sent with unsupported language
        $this->client->request(method: 'GET', uri: '/profile', server: ['HTTP_ACCEPT_LANGUAGE' => 'de']);
        $this->serializerAndAssertJsonResponse($expectedProfile);
    }

    public function testGetProfileInFR(): void
    {
        $this->client->request(method: 'GET', uri: '/profile', server: ['HTTP_ACCEPT_LANGUAGE' => 'fr']);

        $expectedProfile = $this->profileService->getProfile('fr');
        $this->serializerAndAssertJsonResponse($expectedProfile);
    }

    /**
     * @dataProvider dataProviderNotFound
     */
    public function testGetProfileNotFound(string $locale): void
    {
        // Remove profile in "fr" version
        $profile = $this->profileService->getProfile($locale);
        $manager = static::getContainer()->get('doctrine')->getManager();
        $manager->remove($profile);
        $manager->flush();

        $this->client->request(method: 'GET', uri: '/profile', server: ['HTTP_ACCEPT_LANGUAGE' => $locale]);

        $expectedException = new ProfileNotFoundException();

        $this->serializeAndAssertJsonResponseHttpException($expectedException, $locale, Response::HTTP_NOT_FOUND);
    }
}
