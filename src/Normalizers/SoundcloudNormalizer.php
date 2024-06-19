<?php

declare(strict_types=1);

namespace Dealroom\SocialsHelpers\Normalizers;

class SoundcloudNormalizer extends AbstractNormalizer
{
    public const PLATFORM = 'soundcloud';

    public const PLATFORM_NAME = 'Soundcloud Profile';

    protected string $pattern = '/https?:\/\/soundcloud\.com\/([\pL\d\-\_]+)/';

    protected string $normalizedUrl = 'https://soundcloud.com/%s';

    protected array|int $idPosition = 1;
}
