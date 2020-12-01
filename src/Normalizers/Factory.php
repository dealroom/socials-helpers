<?php

namespace Dealroom\SocialsHelpers\Normalizers;

use Dealroom\SocialsHelpers\Exceptions\NormalizeException;
use Dealroom\SocialsHelpers\Parser;

class Factory
{
    public static $normalizers = [
        Parser::PLATFORM_FACEBOOK_PAGE => FacebookPageNormalizer::class,
        Parser::PLATFORM_FACEBOOK_PROFILE => FacebookProfileNormalizer::class,
        Parser::PLATFORM_TWITTER => TwitterNormalizer::class,
        Parser::PLATFORM_LINKEDIN_COMPANY => LinkedinCompanyNormalizer::class,
        Parser::PLATFORM_LINKEDIN_SHOWCASE => LinkedinShowcaseNormalizer::class,
    ];

    /**
     * @param string $platform
     * @return NormalizerInterface
     */
    public static function getForPlatform(string $platform): NormalizerInterface
    {
        if (!isset(self::$normalizers[$platform])) {
            throw new NormalizeException(sprintf('No normalizer found for platform %s', $platform));
        }

        return new self::$normalizers[$platform];
    }
}