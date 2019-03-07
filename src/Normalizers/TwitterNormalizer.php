<?php

namespace Dealroom\SocialsHelpers\Normalizers;

use Dealroom\SocialsHelpers\Exceptions\NormalizeException;
use Dealroom\SocialsHelpers\Parser;

class TwitterNormalizer extends AbstractNormalizer
{
    public function normalize(string $url): string
    {
        $url = parent::normalize($url);

        $result = preg_match(
            Parser::TWITTER_URL_REGEX,
            $url,
            $matches
        );

        if (!$result) {
            throw new NormalizeException(sprintf('Twitter pattern didn\'t match for %s', $url));
        }

        if ($matches[4] === 'share') {
            throw new NormalizeException('Twitter name can not be equal to share');
        }

        if (strlen($matches[4]) > 15) {
            throw new NormalizeException(sprintf('Twitter name can not be longer than 15 chars: %s', $matches[4]));
        }

        return 'https://twitter.com/'.$matches[4];
    }
}