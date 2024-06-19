<?php

declare(strict_types=1);

namespace Dealroom\SocialsHelpers\Normalizers;

class TikTokNormalizer extends AbstractNormalizer
{
    public const PLATFORM = 'tiktok';

    public const PLATFORM_NAME = 'TikTok Profile';

    protected string $pattern = '/https?:\/\/(?:www\.)?tiktok\.com\/(@[\pL\d\_\.]{1,24})/';

    protected string $normalizedUrl = 'https://www.tiktok.com/%s';

    protected array|int $idPosition = 1;
}
