<?php

declare(strict_types=1);

namespace Tests\Dealroom\SocialsHelpers;

use Dealroom\SocialsHelpers\Factory;
use Dealroom\SocialsHelpers\Normalizers\TwitterNormalizer;
use PHPUnit\Framework\TestCase;

class ResultTest extends TestCase
{
    public function testResultMethods(): void
    {
        $url = 'http://twitter.com/Dealroom';

        $result = Factory::parseUrl($url);

        $this->assertEquals(TwitterNormalizer::getPlatform(), $result->getPlatform());
        $this->assertEquals($url, $result->getUrl());
        $this->assertEquals('https://twitter.com/dealroom', $result->getNormalizedUrl());
        $this->assertEquals('dealroom', $result->getId());
    }
}
