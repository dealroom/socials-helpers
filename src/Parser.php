<?php

declare(strict_types=1);

namespace Dealroom\SocialsHelpers;

use Dealroom\SocialsHelpers\Exceptions\InvalidPlatformException;
use Dealroom\SocialsHelpers\Exceptions\InvalidUrlException;

class Parser
{
    public const PLATFORM_FACEBOOK_PAGE = 'facebook_page';
    public const PLATFORM_FACEBOOK_PROFILE = 'facebook_profile';
    public const PLATFORM_TWITTER = 'twitter';
    public const PLATFORM_LINKEDIN_COMPANY = 'linkedin_company';
    public const PLATFORM_LINKEDIN_SHOWCASE = 'linkedin_showcase';
    public const PLATFORM_LINKEDIN_SCHOOL = 'linkedin_school';
    public const PLATFORM_LINKEDIN_PROFILE = 'linkedin_profile';

    public const SOCIAL_MEDIA_PATTERNS = [
        self::PLATFORM_FACEBOOK_PAGE => self::FACEBOOK_PAGE_URL_REGEX,
        self::PLATFORM_FACEBOOK_PROFILE => self::FACEBOOK_PROFILE_URL_REGEX,
        self::PLATFORM_TWITTER => self::TWITTER_URL_REGEX,
        self::PLATFORM_LINKEDIN_COMPANY => self::LINKEDIN_COMPANY_REGEX,
        self::PLATFORM_LINKEDIN_SHOWCASE => self::LINKEDIN_SHOWCASE_REGEX,
        self::PLATFORM_LINKEDIN_SCHOOL => self::LINKEDIN_SCHOOL_REGEX,
        self::PLATFORM_LINKEDIN_PROFILE => self::LINKEDIN_PROFILE_REGEX,
    ];

    // phpcs:ignore Generic.Files.LineLength.TooLong
    public const FACEBOOK_PAGE_URL_REGEX = '/http(s)?:\/\/(www\.|m\.|mobile\.|business\.|web\.|p-upload\.|[a-z]{2}-[a-z]{2}\.)?(facebook|fb)\.com\/(?!sharer\/)(?!sharer.php)(?!share.php)(?!people\/_\/)(?!profile\.php)(pages\/)?([\p{L}0-9_\-\.\+]+)(\/\d+)?\/?/';

    // phpcs:ignore Generic.Files.LineLength.TooLong
    public const FACEBOOK_PROFILE_URL_REGEX = '/http(s)?:\/\/(www\.)?(facebook|fb)\.com\/(people\/_\/|profile\.php\?id=)(\d+)\/?/';

    public const TWITTER_URL_REGEX = '/http(s)?:\/\/(www\.)?twitter\.com\/@?(#!\/)?([A-z0-9_]+)\/?/';

    public const LINKEDIN_COMPANY_REGEX = '/http(s)?:\/\/(www\.)?linkedin\.com\/company\/?([\p{L}\d&\'.\-–_®]+)\/?/u';

    public const LINKEDIN_SHOWCASE_REGEX = '/http(s)?:\/\/(www\.)?linkedin\.com\/showcase\/?([\p{L}\d&\'.\-–_®]+)\/?/u';

    public const LINKEDIN_SCHOOL_REGEX = '/http(s)?:\/\/(www\.)?linkedin\.com\/school\/?([\p{L}\d&\'.\-–_®]+)\/?/u';

    public const LINKEDIN_PROFILE_REGEX = '/(?:https?:)?\/\/(?:[\w]+\.)?linkedin\.com\/in\/([\w\-\_À-ÿ%]+)\/?/u';

    /**
     * @param string $url
     * @param array $allowedPlatforms
     * @return Result
     */
    public function parseUrl(string $url, array $allowedPlatforms = []): Result
    {
        $url = Utils::cleanUrl($url);

        if (!Utils::isValidUrl($url)) {
            throw new InvalidUrlException(sprintf('Invalid url %s', $url));
        }

        $platform = $this->identifyPlatform($url, $allowedPlatforms);

        return new Result($platform, $url);
    }

    /**
     * @param string $url
     * @param array $allowedTypes
     * @return string
     */
    private function identifyPlatform(string $url, array $allowedTypes = []): string
    {
        foreach (self::SOCIAL_MEDIA_PATTERNS as $platform => $pattern) {
            if ($allowedTypes && !in_array($platform, $allowedTypes)) {
                continue;
            }
            if (preg_match($pattern, rawurldecode($url))) {
                return $platform;
            }
        }

        throw new InvalidPlatformException();
    }
}
