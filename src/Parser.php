<?php

namespace Dealroom\SocialsHelpers;

use Dealroom\SocialsHelpers\Exceptions\InvalidPlatformException;
use Dealroom\SocialsHelpers\Exceptions\InvalidUrlException;

class Parser
{
    const PLATFORM_FACEBOOK_PAGE = 'facebook_page';
    const PLATFORM_FACEBOOK_PROFILE = 'facebook_profile';
    const PLATFORM_TWITTER = 'twitter';

    const SOCIAL_MEDIA_PATTERNS = [
        self::PLATFORM_FACEBOOK_PAGE => self::FACEBOOK_PAGE_URL_REGEX,
        self::PLATFORM_FACEBOOK_PROFILE => self::FACEBOOK_PROFILE_URL_REGEX,
        self::PLATFORM_TWITTER => self::TWITTER_URL_REGEX,
    ];

    const FACEBOOK_PAGE_URL_REGEX = '/http(s)?:\/\/(www\.|m\.|mobile\.|business\.|web\.|p-upload\.|[a-z]{2}-[a-z]{2}\.)?(facebook|fb)\.com\/(?!share.php)(?!people\/_\/)(?!profile\.php)(pages\/)?([\p{L}0-9_\-\.\+]+)(\/\d+)?\/?/';

    const FACEBOOK_PROFILE_URL_REGEX = '/http(s)?:\/\/(www\.)?(facebook|fb)\.com\/(people\/_\/|profile\.php\?id=)(\d+)\/?/';

    const TWITTER_URL_REGEX = '/http(s)?:\/\/(www\.)?twitter\.com\/@?(#!\/)?([A-z0-9_]+)\/?/';

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