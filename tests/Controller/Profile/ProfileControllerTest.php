<?php

namespace App\Tests\Controller\Profile;

use App\Entity\Profile\Profile;
use App\Exception\Profile\ProfileNotFoundException;
use App\Service\Profile\ProfileService;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Translation\Translator;

class ProfileControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private Serializer $serializer;
    private ProfileService $profileService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->serializer = static::getContainer()->get('serializer');

        $this->profileService = static::getContainer()->get(ProfileService::class);
    }

    protected function assertGetProfileSuccessfulResponse(Profile $expectedProfile): void
    {
        static::assertResponseIsSuccessful();
        static::assertResponseStatusCodeSame(Response::HTTP_OK);
        static::assertResponseFormatSame('json');
        static::assertEquals(
            $this->serializer->serialize(
                $expectedProfile,
                'json',
                ['json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS]
            ),
            $this->client->getResponse()->getContent()
        );
    }

    public function testGetProfileInEN(): void
    {
        // Default language if no ACCEPT_LANGUAGE header sent
        $this->client->request('GET', '/profile');
        $expectedProfile = $this->profileService->getProfile('en');
        $this->assertGetProfileSuccessfulResponse($expectedProfile);

        // Default language if ACCEPT_LANGUAGE header sent with unsupported language
        $this->client->request('GET', '/profile', [], [], ['HTTP_ACCEPT_LANGUAGE' => 'de']);
        $this->assertGetProfileSuccessfulResponse($expectedProfile);
    }

    public function testGetProfileInFR(): void
    {
        $this->client->request('GET', '/profile', [], [], ['HTTP_ACCEPT_LANGUAGE' => 'fr']);

        $expectedProfile = $this->profileService->getProfile('fr');
        $this->assertGetProfileSuccessfulResponse($expectedProfile);
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

        static::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        static::assertResponseFormatSame('json');
        static::assertEquals(
            $this->serializer->serialize(
                [
                    'code' => $expectedException->getStatusCode(),
                    'message' => $translator->trans($expectedException->getMessage()),
                ],
                'json',
                ['json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS]
            ),
            $this->client->getResponse()->getContent()
        );
    }
}
