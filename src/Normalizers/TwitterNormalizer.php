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
            throw new NormalizeException();
        }

        return 'https://twitter.com/'.$matches[4];
    }
}