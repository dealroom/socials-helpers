<?php

namespace Dealroom\SocialsHelpers\Normalizers;

use Dealroom\SocialsHelpers\Parser;

class FacebookProfileNormalizer extends AbstractNormalizer
{
    public function normalize(string $url): string
    {
        preg_match(
            Parser::FACEBOOK_PROFILE_URL_REGEX,
            $url,
            $matches
        );

        return 'https://www.facebook.com/'.$matches[4].$matches[5];
    }
}