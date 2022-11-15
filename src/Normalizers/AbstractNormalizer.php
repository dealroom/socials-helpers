<?php declare(strict_types=1);

namespace Dealroom\SocialsHelpers\Normalizers;

use Dealroom\SocialsHelpers\Utils;

abstract class AbstractNormalizer implements NormalizerInterface
{
    public function normalize(string $url): string
    {
        return Utils::cleanUrl($url);
    }

    public function normalizeToId(string $url): string
    {
        return '';
    }
}