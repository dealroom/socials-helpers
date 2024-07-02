<?php

declare(strict_types=1);

namespace Dealroom\SocialsHelpers\Normalizers;

class LinkedinShowcaseNormalizer extends AbstractNormalizer
{
    public static function getPlatform(): string
    {
        return 'linkedin_showcase';
    }

    protected string $pattern = '/http(s)?:\/\/(www\.)?linkedin\.com\/showcase\/?([\p{L}\d&\'.\-–_®]+)\/?/u';

    protected string $normalizedUrl = 'https://www.linkedin.com/showcase/%s/';

    protected array|int $idPosition = 3;
}
