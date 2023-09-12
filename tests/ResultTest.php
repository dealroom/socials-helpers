<?php

declare(strict_types=1);

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
        $this->assertEquals(strtolower($url), $result->getUrl());
        $this->assertEquals('https://twitter.com/dealroom', $result->getNormalizedUrl());
    }
}
