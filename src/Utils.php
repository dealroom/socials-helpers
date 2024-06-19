<?php

declare(strict_types=1);

namespace Dealroom\SocialsHelpers;

class Utils
{
    /**
     * @param string $url
     * @return string
     */
    public static function cleanUrl(string $url, array $settings = []): string
    {
        $settings = array_merge([
            'forceHTTPS' => false,
            'forceLowerCase' => false,
        ], $settings);

        if ($settings['forceLowerCase'] === true) {
            $url = mb_strtolower(trim($url));
        }

        if ($settings['forceHTTPS'] === true && str_starts_with($url, 'http://')) {
            $url = preg_replace('/^http:\/\//', 'https://', $url);
        }

        // Clean usages of #!
        $url = str_replace('#!/', '/', $url);

        // Clean weird double slashes //
        if (strlen($url) > 7) {
            while (strpos($url, '//', 7) !== false) {
                $url = substr_replace($url, '', strpos($url, '//', 7), 1);
            }
        }

        return $url;
    }

    /**
     * @param string $url
     * @return bool
     */
    public static function isValidUrl(string $url): bool
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        return true;
    }
}
