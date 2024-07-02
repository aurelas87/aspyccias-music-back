<?php

namespace App\Tests\Controller\Release;

use App\Model\Release\ReleaseType;
use App\Repository\Release\ReleaseRepository;
use App\Tests\Commons\ExpectedReleasesTrait;
use App\Tests\Controller\JsonResponseTestCase;

class ReleaseControllerTest extends JsonResponseTestCase
{
    use ExpectedReleasesTrait;

    /**
     * @throws \Exception
     */
    public function dataProviderListReleases(): array
    {
        return $this->buildReleaseListUseCases(false);
    }

    /**
     * @dataProvider dataProviderListReleases
     */
    public function testListReleases(
        string $locale,
        string $type,
        int $nbItems,
        array $items
    ): void {
        $this->client->request(
            method: 'GET',
            uri: '/releases',
            parameters: ['type' => $type],
            server: ['HTTP_ACCEPT_LANGUAGE' => $locale]
        );

        $this->serializerAndAssertJsonResponse(
            expectedContent: $items,
            contextGroups: ['default', 'list']
        );
    }

    public function testListReleasesEmpty(): void
    {
        $manager = $this->getContainer()->get('doctrine')->getManager();
        $allSingles = $this->getContainer()->get(ReleaseRepository::class)->findBy(['type' => ReleaseType::single]);
        foreach ($allSingles as $single) {
            $manager->remove($single);
        }
        $manager->flush();

        $this->client->request(
            method: 'GET',
            uri: '/releases',
            parameters: ['type' => 'single'],
            server: ['HTTP_ACCEPT_LANGUAGE' => 'fr']
        );

        $this->serializerAndAssertJsonResponse([]);
    }

    public function dataProviderListReleasesWithInvalidType(): array
    {
        $useCases = [];
        foreach (['en', 'fr'] as $locale) {
            foreach (self::INVALID_TYPE_USE_CASES as $useCaseName => $useCase) {
                $useCases[$useCaseName." $locale"] = [...['locale' => $locale], ...$useCase];
            }
        }

        return $useCases;
    }

    /**
     * @dataProvider dataProviderListReleasesWithInvalidType
     */
    public function testListReleasesWithInvalidType(
        string $locale,
        array $queryParameters,
        string $expectedExceptionClass
    ): void {
        $this->client->request(
            method: 'GET',
            uri: '/releases',
            parameters: $queryParameters,
            server: ['HTTP_ACCEPT_LANGUAGE' => $locale]
        );

        $expectedException = new $expectedExceptionClass();

        $this->serializeAndAssertJsonResponseHttpException($expectedException, $locale);
    }
}
