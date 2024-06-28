<?php

declare(strict_types=1);

namespace Tests\Dealroom\SocialsHelpers;

use Dealroom\SocialsHelpers\Exceptions\NormalizeException;
use Dealroom\SocialsHelpers\Normalizers\FacebookPageNormalizer;
use Dealroom\SocialsHelpers\Normalizers\FacebookProfileNormalizer;
use Dealroom\SocialsHelpers\Normalizers\Factory;
use Dealroom\SocialsHelpers\Normalizers\LinkedinCompanyNormalizer;
use Dealroom\SocialsHelpers\Normalizers\NormalizerInterface;
use Dealroom\SocialsHelpers\Normalizers\TwitterNormalizer;
use PHPUnit\Framework\TestCase;

class NormalizerFactoryTest extends TestCase
{
    public function testNormalizersFactory(): void
    {
        $twitterNormalizer = Factory::getForPlatform(TwitterNormalizer::getPlatform());
        $facebookPageNormalizer = Factory::getForPlatform(FacebookPageNormalizer::getPlatform());
        $facebookProfileNormalizer = Factory::getForPlatform(FacebookProfileNormalizer::getPlatform());
        $linkedinCompanyProfileNormalizer = Factory::getForPlatform(LinkedinCompanyNormalizer::getPlatform());

        $this->assertInstanceOf(TwitterNormalizer::class, $twitterNormalizer);
        $this->assertInstanceOf(FacebookPageNormalizer::class, $facebookPageNormalizer);
        $this->assertInstanceOf(FacebookProfileNormalizer::class, $facebookProfileNormalizer);
        $this->assertInstanceOf(LinkedinCompanyNormalizer::class, $linkedinCompanyProfileNormalizer);
        $this->assertInstanceOf(NormalizerInterface::class, $twitterNormalizer);
        $this->assertInstanceOf(NormalizerInterface::class, $facebookPageNormalizer);
        $this->assertInstanceOf(NormalizerInterface::class, $facebookProfileNormalizer);
        $this->assertInstanceOf(NormalizerInterface::class, $linkedinCompanyProfileNormalizer);
    }

    public function testNormalizersFactoryException(): void
    {
        $this->expectException(NormalizeException::class);

        Factory::getForPlatform('foo');
    }

    public function testAddNormalizer(): void
    {
        $fakeNormalizer = new class implements NormalizerInterface {
            public static function getPlatform(): string
            {
                return 'foo';
            }

            public function normalize(string $url): string
            {
                return $url;
            }

            public function normalizeToId(string $url, array $settings = []): string
            {
                return $url;
            }

            public function getPattern(): string
            {
                return '/foo/';
            }

            public function setPattern(string $pattern): void
            {
                // Do nothing
            }
        };

        Factory::addNormalizer($fakeNormalizer);

        $this->assertInstanceOf($fakeNormalizer::class, Factory::getForPlatform($fakeNormalizer::getPlatform()));
    }
}
