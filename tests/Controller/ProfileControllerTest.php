<?php

namespace App\Tests\Controller;

use App\Exception\Profile\ProfileNotFoundException;
use App\Service\ProfileService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProfileControllerTest extends WebTestCase
{
    public function testGetProfile(): void
    {
        $client = static::createClient();
        $client->request('GET', '/profile');

        $expectedProfile = static::getContainer()->get(ProfileService::class)->getAspycciasProfile();
        $serializer = static::getContainer()->get('serializer');

        static::assertResponseIsSuccessful();
        static::assertResponseStatusCodeSame(200);
        static::assertResponseFormatSame('json');
        static::assertEquals(
            $serializer->serialize($expectedProfile, 'json'),
            $client->getResponse()->getContent()
        );
    }

    public function testGetUnknownProfile(): void
    {
        $client = static::createClient();

        $profile = static::getContainer()->get(ProfileService::class)->getAspycciasProfile();
        $manager = static::getContainer()->get('doctrine')->getManager();
        $manager->remove($profile);
        $manager->flush();

        $client->request('GET', '/profile');

        $expectedException = new ProfileNotFoundException('Aspyccias');
        $serializer = static::getContainer()->get('serializer');

        static::assertResponseStatusCodeSame(404);
        static::assertResponseFormatSame('json');
        static::assertEquals(
            $serializer->serialize(
                [
                    'code' => 404,
                    'message' => $expectedException->getMessage(),
                ],
                'json',
                [
                    'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS,
                ]
            ),
            $client->getResponse()->getContent()
        );
    }
}
