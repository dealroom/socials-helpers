<?php

declare(strict_types=1);

namespace Dealroom\SocialsHelpers;

use Dealroom\SocialsHelpers\Exceptions\InvalidPlatformException;
use Dealroom\SocialsHelpers\Exceptions\InvalidUrlException;
use Dealroom\SocialsHelpers\Normalizers\Factory;

class Parser
{
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
        foreach (Factory::$normalizers as $platform => $normalizer) {
            if ($allowedTypes && !in_array($platform, $allowedTypes)) {
                continue;
            }
            $normalizerInstance = Factory::getForPlatform($platform);
            if (preg_match($normalizerInstance->getPattern(), rawurldecode($url))) {
                return $normalizerInstance::PLATFORM;
            }
        }

        throw new InvalidPlatformException();
    }
}
