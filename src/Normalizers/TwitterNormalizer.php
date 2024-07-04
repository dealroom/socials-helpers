<?php

declare(strict_types=1);

namespace Dealroom\SocialsHelpers\Normalizers;

use Dealroom\SocialsHelpers\Exceptions\NormalizeException;

class TwitterNormalizer extends AbstractNormalizer
{
    public static function getPlatform(): string
    {
        return 'twitter';
    }

    protected string $pattern = '/https?:\/\/(?:www\.)?twitter\.com\/@?(?:#!\/)?([A-z0-9_]+)\/?/';

    protected string $normalizedUrl = 'https://twitter.com/%s';

    protected array|int $idPosition = 1;

    protected function match(string $url): array
    {
        $result = preg_match($this->getPattern(), $url, $matches);

        if (!$result) {
            throw new NormalizeException(
                sprintf("%s pattern didn't match for %s", static::getPlatform(), $url)
            );
        }

        if ($matches[1] === 'share') {
            throw new NormalizeException(
                sprintf('%s name can not be equal to share', static::getPlatform())
            );
        }

        if (strlen($matches[1]) > 15) {
            throw new NormalizeException(
                sprintf('%s name can not be longer than 15 chars: %s', static::getPlatform(), $matches[1])
            );
        }

        return $matches;
    }
}
