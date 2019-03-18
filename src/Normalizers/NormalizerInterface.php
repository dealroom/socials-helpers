<?php

namespace Dealroom\SocialsHelpers\Normalizers;

interface NormalizerInterface
{
    public function normalize(string $url): string;

    public function normalizeToId(string $url);
}