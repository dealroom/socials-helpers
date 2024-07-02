<?php

declare(strict_types=1);

namespace Dealroom\SocialsHelpers\Normalizers;

class FacebookProfileNormalizer extends AbstractNormalizer
{
    public static function getPlatform(): string
    {
        return 'facebook_profile';
    }

    protected string $pattern = '/http(s)?:\/\/(www\.)?(facebook|fb)\.com\/(people\/_\/|profile\.php\?id=)(\d+)\/?/';

    protected string $normalizedUrl = 'https://www.facebook.com/profile.php?id=%s';

    protected array|int $idPosition = 5;
}
