<?php

declare(strict_types=1);

namespace Tests\Dealroom\SocialsHelpers;

use Dealroom\SocialsHelpers\Exceptions\InvalidPlatformException;
use Dealroom\SocialsHelpers\Exceptions\InvalidUrlException;
use Dealroom\SocialsHelpers\Exceptions\NormalizeException;
use Dealroom\SocialsHelpers\Factory;
use Dealroom\SocialsHelpers\Result;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    public function testParserCreate(): void
    {
        $result = Factory::parseUrl('http://twitter.com/dealroom');

        $this->assertInstanceOf(Result::class, $result);
    }

    public function testParserCreateErrors(): void
    {
        $this->expectException(InvalidUrlException::class);
        Factory::parseUrl('foo');

        $this->expectException(InvalidPlatformException::class);
        Factory::parseUrl('http://huitter.com/adasd');

        $this->expectException(NormalizeException::class);
        Factory::parseUrl('http://twitter.com/adasdadasdadasd1');
    }
}
