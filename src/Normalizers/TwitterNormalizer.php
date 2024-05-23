<?php

declare(strict_types=1);

namespace Dealroom\SocialsHelpers\Normalizers;

use Dealroom\SocialsHelpers\Exceptions\NormalizeException;
use Dealroom\SocialsHelpers\Parser;

class TwitterNormalizer extends AbstractNormalizer
{
    protected function getDomain(): string
    {
        return 'twitter';
    }

    protected function getPattern(): string
    {
        return Parser::TWITTER_URL_REGEX;
    }

    public function normalize(string $url): string
    {

        $url = parent::normalize($url);

        $matches = $this->match($url);

        return 'https://' . $this->getDomain() . '.com/' . $matches[4];
    }

    public function normalizeToId(string $url): string
    {
        $url = parent::normalize($url);

        $matches = $this->match($url);

        return $matches[4];
    }

    /**
     * @param string $url
     *
     * @return array
     */
    private function match(string $url): array
    {
        $result = preg_match($this->getPattern(), $url, $matches);

        if (!$result) {
            throw new NormalizeException(
                sprintf('%s pattern didn\'t match for %s', ucfirst($this->getDomain()), $url)
            );
        }

        if ($matches[4] === 'share') {
            throw new NormalizeException(
                sprintf('%s name can not be equal to share', ucfirst($this->getDomain()))
            );
        }

        if (strlen($matches[4]) > 15) {
            throw new NormalizeException(
                sprintf('%s name can not be longer than 15 chars: %s', ucfirst($this->getDomain()), $matches[4])
            );
        }

        return $matches;
    }
}
