<?php

namespace App\Tests\Controller\Release;

use App\Exception\Release\ReleaseNotFoundException;
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
        return $this->buildReleaseListUseCases();
    }

    /**
     * @dataProvider dataProviderListReleases
     */
    public function testListReleases(
        string $locale,
        ReleaseType $type,
        int $nbItems,
        array $items
    ): void {
        $this->client->request(
            method: 'GET',
            uri: '/releases/'.$type->name,
            server: ['HTTP_ACCEPT_LANGUAGE' => $locale]
        );

        $this->serializeAndAssertJsonResponse(
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
            uri: '/releases/single',
            server: ['HTTP_ACCEPT_LANGUAGE' => 'fr']
        );

        $this->serializeAndAssertJsonResponse([]);
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
        ?string $type,
        string $expectedExceptionClass,
        string $expectedExceptionMessage
    ): void {
        $uri = '/releases/';
        if (!\is_null($type)) {
            $uri .= $type;
        }

        $this->client->request(
            method: 'GET',
            uri: $uri,
            server: ['HTTP_ACCEPT_LANGUAGE' => $locale]
        );

        $expectedException = new $expectedExceptionClass($expectedExceptionMessage);

        $this->serializeAndAssertJsonResponseHttpException($expectedException, $locale);
    }

    /**
     * @throws \Exception
     */
    public function dataProviderReleaseDetails(): array
    {
        return $this->buildReleaseDetailsUseCases();
    }

    /**
     * @dataProvider dataProviderReleaseDetails
     */
    public function testReleaseDetails(string $locale, array $release): void
    {
        $this->client->request(
            method: 'GET',
            uri: '/releases/'.$release['slug'],
            server: ['HTTP_ACCEPT_LANGUAGE' => $locale]
        );

        $this->serializeAndAssertJsonResponse(
            expectedContent: $release,
            contextGroups: ['default', 'details']
        );
    }

    /**
     * @dataProvider dataProviderNotFound
     */
    public function testReleaseDetailsNotFound(string $locale): void
    {
        $this->client->request(
            method: 'GET',
            uri: '/releases/release-title-14',
            server: ['HTTP_ACCEPT_LANGUAGE' => $locale]
        );

        $expectedException = new ReleaseNotFoundException();

        $this->serializeAndAssertJsonResponseHttpException($expectedException, $locale);
    }
}
