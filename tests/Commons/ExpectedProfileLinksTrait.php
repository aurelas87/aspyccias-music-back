<?php

namespace App\Tests\Commons;

trait ExpectedProfileLinksTrait
{
    protected array $expectedProfileLinks = [
        ['name' => 'facebook', 'link' => 'https://www.facebook.com', 'position' => 1],
        ['name' => 'instagram', 'link' => 'https://www.instagram.com', 'position' => 2],
        ['name' => 'youtube', 'link' => 'https://www.youtube.com', 'position' => 3],
        ['name' => 'spotify', 'link' => 'https://www.spotify.com', 'position' => 4],
    ];
}
