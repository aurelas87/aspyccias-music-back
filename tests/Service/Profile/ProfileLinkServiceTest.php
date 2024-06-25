<?php

namespace App\Tests\Service\Profile;

use App\Entity\Profile\ProfileLink;
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

        $this->profileLinkService = static::getContainer()->get(ProfileLinkService::class);
    }

    public function testListProfileLinks(): void
    {
        $profileLinks = $this->profileLinkService->listProfileLinks();

        static::assertIsArray($profileLinks);
        static::assertCount(3, $profileLinks);

        static::assertTrue($profileLinks[0] instanceof ProfileLink);
        static::assertTrue($profileLinks[1] instanceof ProfileLink);
        static::assertTrue($profileLinks[2] instanceof ProfileLink);

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
        $manager = static::getContainer()->get('doctrine')->getManager();
        foreach ($profileLinks as $profileLink) {
            $manager->remove($profileLink);
        }
        $manager->flush();

        $profileLinks = $this->profileLinkService->listProfileLinks();

        static::assertIsArray($profileLinks);
        static::assertCount(0, $profileLinks);
    }
}
