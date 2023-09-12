<?php

declare(strict_types=1);

namespace Dealroom\SocialsHelpers\Normalizers;

use Dealroom\SocialsHelpers\Parser;

class FacebookProfileNormalizer extends AbstractNormalizer
{
    public function normalize(string $url): string
    {
        $matches = $this->match($url);

        return 'https://www.facebook.com/'.$matches[4].$matches[5];
    }

    public function normalizeToId(string $url): string
    {
        $matches = $this->match($url);

        return $matches[5];
    }

    private function match(string $url): array
    {
        preg_match(
            Parser::FACEBOOK_PROFILE_URL_REGEX,
            $url,
            $matches
        );

        return $matches;
    }
}
