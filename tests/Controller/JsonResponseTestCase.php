<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Translation\Translator;

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

    protected function serializerAndAssertJsonResponse(
        $expectedContent,
        ?array $contextGroups = null,
        int $statusCode = Response::HTTP_OK
    ): void {
        $context = ['json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS];

        if (\is_array($contextGroups)) {
            $context['groups'] = $contextGroups;
        }

        static::assertResponseStatusCodeSame($statusCode);
        static::assertResponseFormatSame('json');
        static::assertSame(
            $this->serializer->serialize(
                $expectedContent,
                'json',
                $context
            ),
            $this->client->getResponse()->getContent()
        );
    }

    public function serializeAndAssertJsonResponseHttpException(HttpException $expectedException, string $locale): void
    {
        /** @var Translator $translator */
        $translator = static::getContainer()->get('translator');
        $translator->setLocale($locale);

        $this->serializerAndAssertJsonResponse(
            expectedContent: [
                'code' => $expectedException->getStatusCode(),
                'message' => $translator->trans($expectedException->getMessage()),
            ],
            statusCode: $expectedException->getStatusCode()
        );
    }

    public function dataProviderNotFound(): array
    {
        return [
            'Not found in en' => ['locale' => 'en'],
            'Not found in fr' => ['locale' => 'fr'],
        ];
    }
}
