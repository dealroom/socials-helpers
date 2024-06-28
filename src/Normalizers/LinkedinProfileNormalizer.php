<?php

declare(strict_types=1);

namespace Dealroom\SocialsHelpers\Normalizers;

class LinkedinProfileNormalizer extends AbstractNormalizer
{
    public static function getPlatform(): string
    {
        return 'linkedin_profile';
    }

    protected string $pattern = '/(?:https?:)?\/\/(?:[\w]+\.)?linkedin\.com\/in\/([\w\-\_À-ÿ%]+)\/?/u';

    protected string $normalizedUrl = 'https://www.linkedin.com/in/%s/';

    protected array|int $idPosition = 1;
}
