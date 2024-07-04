<?php

namespace App\Tests\Service\Profile;

use App\Service\Profile\ProfileLinkService;
use App\Tests\Commons\ExpectedProfileLinksTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProfileLinkServiceTest extends KernelTestCase
{
    use ExpectedProfileLinksTrait;

    protected ProfileLinkService $profileLinkService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->profileLinkService = $this->getContainer()->get(ProfileLinkService::class);
    }

    public function testListProfileLinks(): void
    {
        $profileLinks = $this->profileLinkService->listProfileLinks();

        static::assertIsArray($profileLinks);
        static::assertCount(\count($this->expectedProfileLinks), $profileLinks);

        foreach ($this->expectedProfileLinks as $index => $expectedProfileLink) {
            static::assertSame($expectedProfileLink['name'], $profileLinks[$index]->getName());
            static::assertSame($expectedProfileLink['link'], $profileLinks[$index]->getLink());
            static::assertSame($expectedProfileLink['position'], $profileLinks[$index]->getPosition());
        }
    }

    public function testListProfileLinksIfEmpty(): void
    {
        $profileLinks = $this->profileLinkService->listProfileLinks();

        // Remove the links
        $manager = $this->getContainer()->get('doctrine')->getManager();
        foreach ($profileLinks as $profileLink) {
            $manager->remove($profileLink);
        }
        $manager->flush();

        $profileLinks = $this->profileLinkService->listProfileLinks();

        static::assertIsArray($profileLinks);
        static::assertCount(0, $profileLinks);
    }
}
