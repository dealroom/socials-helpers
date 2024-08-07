[![Test & Release](https://github.com/dealroom/socials-helpers/actions/workflows/main.yml/badge.svg)](https://github.com/dealroom/socials-helpers/actions/workflows/main.yml)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=dealroom_socials-helpers&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=dealroom_socials-helpers)
[![Maintainability](https://api.codeclimate.com/v1/badges/5a5141b6860d07672bba/maintainability)](https://codeclimate.com/github/dealroom/socials-helpers/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/5a5141b6860d07672bba/test_coverage)](https://codeclimate.com/github/dealroom/socials-helpers/test_coverage)

# Socials Helpers

Helper package used for the validation of social links.

## Requirements

This project requires PHP 8.2 or higher with the `mbstring` extension installed.

## Installation

Install via [composer], simply run:

``` bash
composer require dealroom/socials-helpers
```

## Usage

The `Factory` class provides a simple wrapper for the validation functionality, for example, to get normalized URL:

```php
use Dealroom\SocialsHelpers\Factory;
use Dealroom\SocialsHelpers\Normalizers\TwitterNormalizer;

$data = Factory::parseUrl('http://twitter.com/Dealroom', [TwitterNormalizer::getPlatform()])->getNormalizedUrl();

echo $data;

// "https://twitter.com/dealroom"
```

Or if you want to extract social network ID (handle):

```php
use Dealroom\SocialsHelpers\Factory;
use Dealroom\SocialsHelpers\Normalizers\TwitterNormalizer;

$data = Factory::parseUrl('https://twitter.com/dealroom', [TwitterNormalizer::getPlatform()])->getId();

echo $data;

// "dealroom"
```

## Supported Platforms

The following platforms are supported by default:

- Apple Music
- Facebook
- Instagram
- LinkedIn
- Twitter
- YouTube
- TikTok
- SoundCloud
- X
- Spotify

### Registering new platforms

To register a new normalizer, you need to create a new class that implements
the `NormalizerInterface` interface (for example, by extending the `AbstractNormalizer` class).
After that, you need to register the new normalizer in the `Factory` class.

```php
use Dealroom\SocialsHelpers\Normalizers\AbstractNormalizer;
use Dealroom\SocialsHelpers\Normalizers\Factory;
use Dealroom\SocialsHelpers\Factory;

class CustomNormalizer extends AbstractNormalizer
{
    // Implement the interface methods
}

Factory::addNormalizer(CustomNormalizer::class);

$data = Factory::parseUrl('https://custom.com/Dealroom', [CustomNormalizer::getPlatform()])->getNormalizedUrl();
```

## Testing

PHPUnit is used for testing, run:

```bash
./vendor/bin/phpunit
```

## Releases and CI/CD

The release is done automatically using GitHub Actions on every push to the `main` branch.
After the release is done, a new tag is created and pushed to GitHub,
which triggers a new release in [packagist](https://packagist.org/).
