<?php

namespace Dealroom\SocialsHelpers\Normalizers;

use Dealroom\SocialsHelpers\Utils;

abstract class AbstractNormalizer implements NormalizerInterface
{
    public function normalize(string $url): string
    {
        $url = Utils::cleanUrl($url);

        return $url;
    }
}