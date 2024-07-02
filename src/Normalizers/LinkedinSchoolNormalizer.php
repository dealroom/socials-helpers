<?php

declare(strict_types=1);

namespace Dealroom\SocialsHelpers\Normalizers;

class LinkedinSchoolNormalizer extends AbstractNormalizer
{
    public static function getPlatform(): string
    {
        return 'linkedin_school';
    }

    protected string $pattern = '/http(s)?:\/\/(www\.)?linkedin\.com\/school\/?([\p{L}\d&\'.\-–_®]+)\/?/u';

    protected string $normalizedUrl = 'https://www.linkedin.com/school/%s/';

    protected array|int $idPosition = 3;
}
