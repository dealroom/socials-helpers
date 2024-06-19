<?php

declare(strict_types=1);

namespace Dealroom\SocialsHelpers\Normalizers;

use Dealroom\SocialsHelpers\Exceptions\NormalizeException;

class TwitterNormalizer extends AbstractNormalizer
{
    public const PLATFORM = 'twitter';

    public const PLATFORM_NAME = 'Twitter';

    protected string $pattern = '/https?:\/\/(?:www\.)?twitter\.com\/@?(?:#!\/)?([A-z0-9_]+)\/?/';

    protected string $normalizedUrl = 'https://twitter.com/%s';

    protected array|int $idPosition = 1;

    /**
     * @param string $url
     *
     * @return array
     */
    protected function match(string $url): array
    {
        $result = preg_match($this->getPattern(), $url, $matches);

        if (!$result) {
            throw new NormalizeException(
                sprintf("%s pattern didn't match for %s", static::PLATFORM_NAME, $url)
            );
        }

        if ($matches[1] === 'share') {
            throw new NormalizeException(
                sprintf('%s name can not be equal to share', static::PLATFORM_NAME)
            );
        }

        if (strlen($matches[1]) > 15) {
            throw new NormalizeException(
                sprintf('%s name can not be longer than 15 chars: %s', static::PLATFORM_NAME, $matches[4])
            );
        }

        return $matches;
    }
}
