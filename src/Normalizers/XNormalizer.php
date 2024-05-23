<?php

declare(strict_types=1);

namespace Dealroom\SocialsHelpers\Normalizers;

use Dealroom\SocialsHelpers\Exceptions\NormalizeException;
use Dealroom\SocialsHelpers\Parser;

class XNormalizer extends TwitterNormalizer
{
    protected function getDomain(): string
    {
        return 'x';
    }

    protected function getPattern(): string
    {
        return Parser::X_URL_REGEX;
    }
}
