<?php

declare(strict_types=1);

namespace Dealroom\SocialsHelpers\Normalizers;

class InstagramNormalizer extends AbstractNormalizer
{
    public const PLATFORM = 'instagram';

    public const PLATFORM_NAME = 'Instagram Profile';

    protected string $pattern = '/https?:\/\/(?:www\.)?instagram\.com\/(?!about|legal|explore|web)([\pL\d\_\.]{1,30})/';

    protected string $normalizedUrl = 'https://www.instagram.com/%s';

    protected array|int $idPosition = 1;
}
