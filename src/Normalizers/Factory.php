<?php

declare(strict_types=1);

namespace Dealroom\SocialsHelpers\Normalizers;

use Dealroom\SocialsHelpers\Exceptions\NormalizeException;

class Factory
{
    private static array $normalizers = [
        AppleMusicNormalizer::class,
        FacebookPageNormalizer::class,
        FacebookProfileNormalizer::class,
        InstagramNormalizer::class,
        LinkedinCompanyNormalizer::class,
        LinkedinProfileNormalizer::class,
        LinkedinSchoolNormalizer::class,
        LinkedinShowcaseNormalizer::class,
        SoundcloudNormalizer::class,
        SpotifyArtistNormalizer::class,
        TikTokNormalizer::class,
        TwitterNormalizer::class,
        XNormalizer::class,
        YoutubeNormalizer::class,
    ];

    public static function getForPlatform(string $platform): NormalizerInterface
    {
        foreach (self::$normalizers as $normalizer) {
            if ($normalizer::getPlatform() === $platform) {
                return new $normalizer();
            }
        }

        throw new NormalizeException(sprintf('No normalizer found for platform %s', $platform));
    }

    public static function addNormalizer(NormalizerInterface $normalizer): void
    {
        self::$normalizers[] = $normalizer::class;
    }

    public static function getNormalizers(): array
    {
        return self::$normalizers;
    }
}
