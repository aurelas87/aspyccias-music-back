<?php

namespace App\Tests\Controller\Profile;

use App\Service\Profile\ProfileLinkService;
use App\Tests\Commons\ExpectedProfileLinksTrait;
use App\Tests\Controller\JsonResponseTestCase;

class ProfileLinkControllerTest extends JsonResponseTestCase
{
    use ExpectedProfileLinksTrait;

    protected ProfileLinkService $profileLinkService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->profileLinkService = static::getContainer()->get(ProfileLinkService::class);
    }

    public function testListProfileLinks(): void
    {
        $this->client->request('GET', '/profile/links');

        $this->assertJsonResponse($this->expectedProfileLinks);
    }

    public function testListProfileLinksIfEmpty(): void
    {
        $profileLinks = $this->profileLinkService->listProfileLinks();

        // Remove the links
        $manager = static::getContainer()->get('doctrine')->getManager();
        foreach ($profileLinks as $profileLink) {
            $manager->remove($profileLink);
        }
        $manager->flush();

        $this->client->request('GET', '/profile/links');

        $this->assertJsonResponse([]);
    }
}
