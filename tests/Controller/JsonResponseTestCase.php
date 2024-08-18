<?php

namespace App\Tests\Controller;

use App\Helper\ValidationErrorsParser;
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

    protected function serializeAndAssertJsonResponse(
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
        $validationErrorsParser = new ValidationErrorsParser();
        $expectedMessage = $validationErrorsParser->getValidationErrors($expectedException);

        if (\is_null($expectedMessage)) {
            if (\str_starts_with($expectedException->getMessage(), 'No route found')) {
                $expectedMessage = $expectedException->getMessage();
            } else {
                /** @var Translator $translator */
                $translator = static::getContainer()->get('translator');
                $translator->setLocale($locale);
                // Disable fallback to test if the translation exists in this locale
                $translator->setFallbackLocales([]);

                if (!$translator->getCatalogue($locale)->has($expectedException->getMessage())) {
                    throw new \RuntimeException(
                        \sprintf(
                            'Missing translation for "%s" in "%s" language',
                            $expectedException->getMessage(),
                            $locale
                        )
                    );
                }

                $expectedMessage = $translator->trans($expectedException->getMessage());
            }
        }

        $this->serializeAndAssertJsonResponse(
            expectedContent: [
                'code' => $expectedException->getStatusCode(),
                'message' => $expectedMessage,
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
