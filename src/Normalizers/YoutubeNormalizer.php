<?php

declare(strict_types=1);

namespace Dealroom\SocialsHelpers\Normalizers;

class YoutubeNormalizer extends AbstractNormalizer
{
    public static function getPlatform(): string
    {
        return 'youtube';
    }

    // phpcs:disable Generic.Files.LineLength.TooLong
    protected string $pattern = '/https?:\/\/(?:www\.)?youtube.com\/(?:c\/|user\/)?((?:channel\/UC)?[\pL\d_\-\+\.\%\@]+)/u';
    // phpcs:enable Generic.Files.LineLength.TooLong

    protected string $normalizedUrl = 'https://www.youtube.com/%s';

    protected array|int $idPosition = 1;

    protected array $cleanUrlSettings = ['forceLowerCase' => false];
}
