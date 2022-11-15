<?php declare(strict_types=1);

namespace Dealroom\SocialsHelpers;

class Factory
{
    /**
     * @param string $url
     * @param array $allowedTypes
     * @return Result
     */
    public static function parseUrl(string $url, array $allowedTypes = []): Result
    {
        return (new Parser())->parseUrl($url, $allowedTypes);
    }
}