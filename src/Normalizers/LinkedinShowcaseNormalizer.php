<?php declare(strict_types=1);

namespace Dealroom\SocialsHelpers\Normalizers;

use Dealroom\SocialsHelpers\Exceptions\NormalizeException;
use Dealroom\SocialsHelpers\Parser;

class LinkedinShowcaseNormalizer extends AbstractNormalizer
{
    public function normalize(string $url): string
    {
        $matches = $this->match($url);

        return 'https://www.linkedin.com/showcase/'.$matches[3].'/';
    }

    public function normalizeToId(string $url): string
    {
        $matches = $this->match($url);

        return $matches[3];
    }

    private function match(string $url): array
    {
        $result = preg_match(
            Parser::LINKEDIN_SHOWCASE_REGEX,
            rawurldecode($url),
            $matches
        );

        if (!$result) {
            throw new NormalizeException(sprintf('Linkedin showcase pattern didn\'t match for %s', $url));
        }

        return $matches;
    }
}