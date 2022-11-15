<?php declare(strict_types=1);

namespace Dealroom\SocialsHelpers\Normalizers;

use Dealroom\SocialsHelpers\Exceptions\NormalizeException;
use Dealroom\SocialsHelpers\Parser;

class LinkedinProfileNormalizer extends AbstractNormalizer
{
    public function normalize(string $url): string
    {
        $matches = $this->match($url);

        return 'https://www.linkedin.com/in/'.$matches[1].'/';
    }

    public function normalizeToId(string $url): string
    {
        $matches = $this->match($url);

        return $matches[1];
    }

    private function match(string $url): array
    {
        $result = preg_match(
            Parser::LINKEDIN_PROFILE_REGEX,
            rawurldecode($url),
            $matches
        );

        if (!$result) {
            throw new NormalizeException(sprintf('Linkedin profile pattern didn\'t match for %s', $url));
        }

        return $matches;
    }
}