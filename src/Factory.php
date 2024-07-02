<?php

declare(strict_types=1);

namespace Dealroom\SocialsHelpers;

class Factory
{
    public static function parseUrl(string $url, array $allowedPlatforms = []): Result
    {
        return (new Parser())->parseUrl($url, $allowedPlatforms);
    }
}
