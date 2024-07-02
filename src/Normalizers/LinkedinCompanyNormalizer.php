<?php

declare(strict_types=1);

namespace Dealroom\SocialsHelpers\Normalizers;

class LinkedinCompanyNormalizer extends AbstractNormalizer
{
    public static function getPlatform(): string
    {
        return 'linkedin_company';
    }

    protected string $pattern = '/http(s)?:\/\/(www\.)?linkedin\.com\/company\/?([\p{L}\d&\'.\-–_®]+)\/?/u';

    protected string $normalizedUrl = 'https://www.linkedin.com/company/%s/';

    protected array|int $idPosition = 3;
}
