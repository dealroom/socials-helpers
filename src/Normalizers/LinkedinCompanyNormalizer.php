<?php

declare(strict_types=1);

namespace Dealroom\SocialsHelpers\Normalizers;

class LinkedinCompanyNormalizer extends AbstractNormalizer
{
    public const PLATFORM = 'linkedin_company';

    public const PLATFORM_NAME = 'Linkedin Company';

    protected string $pattern = '/http(s)?:\/\/(www\.)?linkedin\.com\/company\/?([\p{L}\d&\'.\-–_®]+)\/?/u';

    protected string $normalizedUrl = 'https://www.linkedin.com/company/%s/';

    protected array|int $idPosition = 3;
}
