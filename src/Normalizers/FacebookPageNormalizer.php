<?php

namespace Dealroom\SocialsHelpers\Normalizers;

use Dealroom\SocialsHelpers\Exceptions\NormalizeException;

class FacebookPageNormalizer extends AbstractNormalizer
{
    public function normalize(string $url): string
    {
        $url = parent::normalize($url);

        if (strpos($url, 'fb.com') !== false) {
            $url = str_replace('fb.com', 'facebook.com', $url);
        }

        if (strpos($url, '/pages/') !== false) {
            $result = preg_match(
                '/(?:(?:http|https):\/\/)?(?:www.)?facebook.com\/pages\/(?:[\w\-\+\.\%\,\(\)]{1,})\/(?:[\d]{1,})/',
                $url,
                $matches
            );
            if ($result) {
                if (strpos($matches[0], 'http://') === 0) {
                    $matches[0] = str_replace('http://', 'https://', $matches[0]);
                }
                if (strpos($matches[0], 'www.facebook.com') === false) {
                    $matches[0] = str_replace('facebook.com', 'www.facebook.com', $matches[0]);
                }
                return $matches[0];
            } else {
                $result = preg_match(
                    '/(?:(?:http|https):\/\/)?(?:www.)?facebook.com\/pages\/(?:[\w\-\+\.\%\,\(\)]{1,})\/(?:[\w\-]{1,})\/(?:[\d]{1,})/',
                    $url,
                    $matches
                );
                if ($result) {
                    if (strpos($matches[0], 'http://') === 0) {
                        $matches[0] = str_replace('http://', 'https://', $matches[0]);
                    }
                    if (strpos($matches[0], 'www.facebook.com') === false) {
                        $matches[0] = str_replace('facebook.com', 'www.facebook.com', $matches[0]);
                    }
                    return $matches[0];
                }

                throw new NormalizeException();
            }
        }

        $result = preg_match(
            '/(?:(?:http|https):\/\/)?(?:www.)?facebook.com\/(?:[\w\-\+\.\%\,\(\)]{1,})/',
            $url,
            $matches
        );
        if ($result) {
            if (strpos($matches[0], 'http://') === 0) {
                $matches[0] = str_replace('http://', 'https://', $matches[0]);
            }
            if (strpos($matches[0], 'www.facebook.com') === false) {
                $matches[0] = str_replace('facebook.com', 'www.facebook.com', $matches[0]);
            }
            return $matches[0];
        }

        throw new NormalizeException();
    }
}