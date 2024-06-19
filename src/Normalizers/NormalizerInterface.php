<?php

namespace Dealroom\SocialsHelpers\Normalizers;

interface NormalizerInterface
{
    public function normalize(string $url): string;

    public function normalizeToId(string $url, array $settings): ?string;

    public function getPattern(): string;

    public function setPattern(string $pattern): void;
}
