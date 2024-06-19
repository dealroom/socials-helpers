<?php

declare(strict_types=1);

namespace Dealroom\SocialsHelpers\Normalizers;

class YoutubeNormalizer extends AbstractNormalizer
{
    public const PLATFORM = 'youtube';

    public const PLATFORM_NAME = 'Youtube Channel';

    protected string $pattern = '/https?:\/\/(?:www\.)?youtube.com\/(?:c\/|user\/)?((?:channel\/UC)?[\pL\d_\-\+\.\%\@]+)/u';

    protected string $normalizedUrl = 'https://www.youtube.com/%s';

    protected array|int $idPosition = 1;

    protected array $cleanUrlSettings = [ 'forceLowerCase' => false ];
}
