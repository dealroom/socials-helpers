<?php

declare(strict_types=1);

namespace Dealroom\SocialsHelpers\Normalizers;

use Dealroom\SocialsHelpers\Exceptions\NormalizeException;

class FacebookPageNormalizer extends AbstractNormalizer
{
    public function normalize(string $url): string
    {
        $url = parent::normalize($url);

        if (str_contains($url, 'fb.com')) {
            $url = str_replace('fb.com', 'facebook.com', $url);
        }

        if (str_contains($url, '/pages/')) {
            // phpcs:ignore Generic.Files.LineLength.TooLong
            $result = preg_match('/(?:(?:http|https):\/\/)?(www\.|m\.|mobile\.|business\.|web\.|p-upload\.|[a-z]{2}-[a-z]{2}\.)?facebook.com\/pages\/[\w\-+.%,()]+\/\d+/', $url, $matches);
            if ($result) {
                $matches = $this->getMatches($matches);

                return $matches[0];
            } else {
                // phpcs:ignore Generic.Files.LineLength.TooLong
                $result = preg_match('/(?:(?:http|https):\/\/)?(www\.|m\.|mobile\.|business\.|web\.|p-upload\.|[a-z]{2}-[a-z]{2}\.)?facebook.com\/pages\/[\w\-+.%,()]+\/[\w\-]+\/\d+/', $url, $matches);
                if ($result) {
                    $matches = $this->getMatches($matches);

                    return $matches[0];
                }

                throw new NormalizeException();
            }
        }

        // phpcs:ignore Generic.Files.LineLength.TooLong
        $result = preg_match('/(?:(?:http|https):\/\/)?(www\.|m\.|mobile\.|business\.|web\.|p-upload\.|[a-z]{2}-[a-z]{2}\.)?facebook.com\/[\w\-+.%,()]+/', $url, $matches);
        if ($result) {
            $matches = $this->getMatches($matches);

            return rtrim($matches[0], '/');
        }

        throw new NormalizeException();
    }

    private function getMatches(array $matches): array
    {
        if (isset($matches[1])) {
            $matches[0] = str_replace($matches[1], '', $matches[0]);
        }

        if (!isset($matches[0])) {
            throw new NormalizeException();
        }

        if (str_starts_with($matches[0], 'http://')) {
            $matches[0] = str_replace('http://', 'https://', $matches[0]);
        }
        if (!str_contains($matches[0], 'www.facebook.com')) {
            $matches[0] = str_replace('facebook.com', 'www.facebook.com', $matches[0]);
        }

        return $matches;
    }
}
