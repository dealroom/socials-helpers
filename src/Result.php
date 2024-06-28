<?php

declare(strict_types=1);

namespace Dealroom\SocialsHelpers;

use Dealroom\SocialsHelpers\Normalizers\Factory as NormalizerFactory;
use Dealroom\SocialsHelpers\Normalizers\NormalizerInterface;

class Result
{
    private string $platform;
    private string $url;
    private string $normalizedUrl;
    private string $id;
    private NormalizerInterface $normalizer;

    public function __construct(string $platform, string $url)
    {
        $this->platform = $platform;
        $this->url = $url;
        $this->normalizer = $this->getNormalizer();
        $this->normalizedUrl = $this->normalizer->normalize($this->url);
        $this->id = $this->normalizer->normalizeToId($this->url);
    }

    public function getPlatform(): string
    {
        return $this->platform;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getNormalizedUrl(): string
    {
        return $this->normalizedUrl;
    }

    public function getId(): string
    {
        return $this->id;
    }

    private function getNormalizer(): NormalizerInterface
    {
        return NormalizerFactory::getForPlatform($this->platform);
    }
}
