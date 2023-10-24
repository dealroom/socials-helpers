[![Test & Release](https://github.com/dealroom/socials-helpers/actions/workflows/main.yml/badge.svg)](https://github.com/dealroom/socials-helpers/actions/workflows/main.yml)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=dealroom_socials-helpers&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=dealroom_socials-helpers)
[![Maintainability](https://api.codeclimate.com/v1/badges/5a5141b6860d07672bba/maintainability)](https://codeclimate.com/github/dealroom/socials-helpers/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/5a5141b6860d07672bba/test_coverage)](https://codeclimate.com/github/dealroom/socials-helpers/test_coverage)

# Social Helpers

Helper package used for the validation of social links.

## Installation & Basic Usage

This project requires PHP 8.1 or higher with the `mbstring` extension.  To install it via [Composer] simply run:

``` bash
composer require dealroom/socials-helpers
```

The `Factory` class provides a simple wrapper for the validation functionality, for example to get normalized URL:

```php
use Dealroom\SocialsHelpers\Factory;
use Dealroom\SocialsHelpers\Parser;

$data = Factory::parseUrl('http://twitter.com/Dealroom', [Parser::PLATFORM_TWITTER])->getNormalizedUrl();

echo $data;

// "https://twitter.com/dealroom"
```

Or if you want to extract social network ID (handle):

```php
use Dealroom\SocialsHelpers\Factory;
use Dealroom\SocialsHelpers\Parser;

$data = Factory::parseUrl('https://twitter.com/dealroom', [Parser::PLATFORM_TWITTER])->getId();

echo $data;

// "dealroom"
```

## Testing

PHPUnit is used for testing, just run:

```bash
vendor/bin/phpunit
```

## Releases and CI/CD

The release is done automatically using GitHub actions on every push to the `main` branch.
After the release is done, a new tag is created and pushed to GitHub which triggers a new release in [packagist](https://packagist.org/).
