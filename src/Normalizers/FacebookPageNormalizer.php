<?php

declare(strict_types=1);

namespace Dealroom\SocialsHelpers\Normalizers;

class FacebookPageNormalizer extends AbstractNormalizer
{
    public static function getPlatform(): string
    {
        return 'facebook_page';
    }

    // phpcs:disable Generic.Files.LineLength.TooLong
    protected string $pattern = '/https?:\/\/(?:www\.|m\.|mobile\.|business\.|web\.|p-upload\.|[a-z]{2}-[a-z]{2}\.)?(?:facebook|fb)\.com\/(?!home\.php)(?:sharer\/|sharer.php|share.php|people\/_\/|profile\.php|pages\/)?(?:(?:([\pL][\pL0-9_\-\+\.\%]+)\/?)+)(\d+)?\/?/u';
    // phpcs:enable Generic.Files.LineLength.TooLong

    protected string $normalizedUrl = 'https://www.facebook.com/%s';

    protected array|int $idPosition = [2, 1];
}
