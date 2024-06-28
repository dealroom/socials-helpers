<?php

declare(strict_types=1);

namespace Dealroom\SocialsHelpers\Normalizers;

use Dealroom\SocialsHelpers\Exceptions\NormalizeException;
use Dealroom\SocialsHelpers\Utils;

abstract class AbstractNormalizer implements NormalizerInterface
{
    protected string $pattern;
    protected string $normalizedUrl;
    protected int|array $idPosition;

    protected array $cleanUrlSettings = ['forceHTTPS' => true, 'forceLowerCase' => true];

    public function normalize(string $url): string
    {
        return sprintf($this->normalizedUrl, $this->normalizeToId($url));
    }

    public function normalizeToId(string $url, array $settings = []): string
    {
        $settings = array_merge($this->cleanUrlSettings, $settings);

        $url = Utils::cleanUrl($url, $settings);

        $matches = $this->match($url);

        if (is_array($this->idPosition)) {
            foreach ($this->idPosition as $position) {
                if (isset($matches[$position])) {
                    return $matches[$position];
                }
            }
            return $matches[$this->idPosition[0] ?? 0];
        }

        return $matches[$this->idPosition];
    }

    public function getPattern(): string
    {
        return $this->pattern;
    }

    public function setPattern(string $pattern): void
    {
        $this->pattern = $pattern;
    }

    protected function match(string $url): array
    {
        $result = preg_match(
            $this->pattern,
            rawurldecode($url),
            $matches
        );

        if (!$result) {
            throw new NormalizeException(
                sprintf("%s pattern didn't match for '%s'", static::getPlatform(), $url)
            );
        }

        return $matches;
    }
}
