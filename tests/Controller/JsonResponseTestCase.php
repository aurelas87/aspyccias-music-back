<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;

class JsonResponseTestCase extends WebTestCase
{
    protected KernelBrowser $client;
    protected Serializer $serializer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->serializer = static::getContainer()->get('serializer');
    }

    protected function serializerAndAssertJsonResponse($expectedContent, int $statusCode = Response::HTTP_OK): void
    {
        static::assertResponseStatusCodeSame($statusCode);
        static::assertResponseFormatSame('json');
        static::assertSame(
            $this->serializer->serialize(
                $expectedContent,
                'json',
                ['json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS]
            ),
            $this->client->getResponse()->getContent()
        );

    }
}
