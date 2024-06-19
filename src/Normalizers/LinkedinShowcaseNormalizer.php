<?php

declare(strict_types=1);

namespace Dealroom\SocialsHelpers\Normalizers;

class LinkedinShowcaseNormalizer extends AbstractNormalizer
{
    public const PLATFORM = 'linkedin_showcase';

    public const PLATFORM_NAME = 'Linkedin Showcase';

    protected string $pattern = '/http(s)?:\/\/(www\.)?linkedin\.com\/showcase\/?([\p{L}\d&\'.\-–_®]+)\/?/u';

    protected string $normalizedUrl = 'https://www.linkedin.com/showcase/%s/';

    protected array|int $idPosition = 3;
}
