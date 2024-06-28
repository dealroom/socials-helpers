<?php

declare(strict_types=1);

namespace Dealroom\SocialsHelpers;

use Dealroom\SocialsHelpers\Exceptions\InvalidPlatformException;
use Dealroom\SocialsHelpers\Exceptions\InvalidUrlException;
use Dealroom\SocialsHelpers\Normalizers\Factory;

class Parser
{
    public function parseUrl(string $url, array $allowedPlatforms = []): Result
    {
        $url = Utils::cleanUrl($url);
        if (!Utils::isValidUrl($url)) {
            throw new InvalidUrlException(sprintf('Invalid url %s', $url));
        }

        $platform = $this->identifyPlatform($url, $allowedPlatforms);

        return new Result($platform, $url);
    }

    private function identifyPlatform(string $url, array $allowedPlatforms = []): string
    {
        foreach (Factory::getNormalizers() as $normalizer) {
            $platform = $normalizer::getPlatform();
            if (!empty($allowedPlatforms) && !in_array($platform, $allowedPlatforms)) {
                continue;
            }

            $normalizerInstance = Factory::getForPlatform($platform);
            if (preg_match($normalizerInstance->getPattern(), rawurldecode($url))) {
                return $normalizerInstance::getPlatform();
            }
        }

        throw new InvalidPlatformException();
    }
}
