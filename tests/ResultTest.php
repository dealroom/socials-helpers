<?php

namespace Tests\Dealroom\SocialsHelpers;

use Dealroom\SocialsHelpers\Factory;
use Dealroom\SocialsHelpers\Parser;
use PHPUnit\Framework\TestCase;

class ResultTest extends TestCase
{
    public function testResultMethods(): void
    {
        $url = 'http://twitter.com/Dealroom';

        $result = Factory::parseUrl($url);

        $this->assertEquals(Parser::PLATFORM_TWITTER, $result->getPlatform());
        $this->assertEquals($url, $result->getUrl());
        $this->assertEquals('https://twitter.com/dealroom', $result->getNormalizedUrl());
    }
}