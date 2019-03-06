<?php

namespace Dealroom\SocialsHelpers;

use Dealroom\SocialsHelpers\Normalizers\Factory as NormalizerFactory;
use Dealroom\SocialsHelpers\Normalizers\NormalizerInterface;

class Result
{
    /**
     * @var string
     */
    private $platform;

    /**
     * @var string
     */
    private $url;

    /**
     * Result constructor.
     *
     * @param string $platform
     * @param string $url
     */
    public function __construct(string $platform, string $url)
    {
        $this->platform = $platform;
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getPlatform(): string
    {
        return $this->platform;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getNormalizedUrl(): string
    {
        $normalizer = $this->getNormalizer();

        return $normalizer ? $normalizer->normalize($this->url) : $this->url;
    }

    /**
     * @return Normalizers\NormalizerInterface
     */
    private function getNormalizer(): NormalizerInterface
    {
        return NormalizerFactory::getForPlatform($this->platform);
    }
}