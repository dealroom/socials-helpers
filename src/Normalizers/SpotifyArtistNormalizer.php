<?php

declare(strict_types=1);

namespace Dealroom\SocialsHelpers\Normalizers;

class SpotifyArtistNormalizer extends AbstractNormalizer
{
    public static function getPlatform(): string
    {
        return 'spotify_artist';
    }

    protected string $pattern = '/(?:https?:\/\/open.spotify.com\/artist\/|spotify:artist:)([a-zA-Z0-9]+)/';

    protected string $normalizedUrl = 'https://open.spotify.com/artist/%s';

    protected array|int $idPosition = 1;

    protected array $cleanUrlSettings = ['forceHTTPS' => true, 'forceLowerCase' => false];
}
