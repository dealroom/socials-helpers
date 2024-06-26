<?php

declare(strict_types=1);

namespace Dealroom\SocialsHelpers\Normalizers;

class XNormalizer extends TwitterNormalizer
{
    public static function getPlatform(): string
    {
        return 'x';
    }

    protected string $pattern = '/https?:\/\/(?:www\.)?x\.com\/@?(?:#!\/)?([A-z0-9_]+)\/?/';

    protected string $normalizedUrl = 'https://x.com/%s';
}
