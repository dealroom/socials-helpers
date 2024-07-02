<?php

declare(strict_types=1);

namespace Dealroom\SocialsHelpers\Normalizers;

class AppleMusicNormalizer extends AbstractNormalizer
{
    public static function getPlatform(): string
    {
        return 'apple_music';
    }

    // phpcs:disable Generic.Files.LineLength.TooLong
    protected string $pattern = '/https?:\/\/(?:itunes|music)\.apple\.com\/(?:[\pL]{2}\/)?artist\/(?:[\pL\d_\-\%\.]+\/)?(?:id)?([\d]+)/';
    // phpcs:enable Generic.Files.LineLength.TooLong

    protected string $normalizedUrl = 'https://music.apple.com/artist/%s';

    protected array|int $idPosition = 1;
}
