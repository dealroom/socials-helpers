<?php

namespace Dealroom\SocialsHelpers\Normalizers;

interface NormalizerInterface
{
    /**
     * Name of the platform e.g., facebook, instagram, TikTok, etc.
     * Note: must be unique among all registered normalizers
     */
    public static function getPlatform(): string;

    public function normalize(string $url): string;

    public function normalizeToId(string $url, array $settings = []): ?string;

    public function getPattern(): string;
}
