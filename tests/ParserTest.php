<?php

namespace Tests\Dealroom\SocialsHelpers;

use Dealroom\SocialsHelpers\Exceptions\InvalidPlatformException;
use Dealroom\SocialsHelpers\Exceptions\InvalidUrlException;
use Dealroom\SocialsHelpers\Factory;
use Dealroom\SocialsHelpers\Result;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    public function testParserCreate(): void
    {
        $result = Factory::parseUrl('http://twitter.com/dealroom');

        $this->assertInstanceOf(Result::class, $result);

        $this->expectException(InvalidUrlException::class);
        Factory::parseUrl('foo');

        $this->expectException(InvalidPlatformException::class);
        Factory::parseUrl('http://huitter.com/adasd');
    }
}