<?php

declare(strict_types=1);

namespace Dealroom\SocialsHelpers\Normalizers;

class AppleMusicNormalizer extends AbstractNormalizer
{
    public const PLATFORM = 'apple_music';

    public const PLATFORM_NAME = 'Apple Music Artist';

    protected string $pattern = '/https?:\/\/(?:itunes|music)\.apple\.com\/(?:[\pL]{2}\/)?artist\/(?:[\pL\d_\-\%\.]+\/)?(?:id)?([\d]+)/';

    protected string $normalizedUrl = 'https://music.apple.com/artist/%s';

    protected array|int $idPosition = 1;
}
