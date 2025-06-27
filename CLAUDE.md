# Socials Helpers - Development Guide

## Project Overview

PHP library for validating and normalizing social media URLs from platforms like Twitter, LinkedIn, Facebook, Instagram, YouTube, TikTok, SoundCloud, Spotify, and Apple Music.

## Technology Stack

- **Language**: PHP >=8.3
- **Package Manager**: Composer
- **Testing**: PHPUnit ^12.0
- **Extension**: ext-mbstring (required)

## Development Commands

```bash
# Install Dependencies
composer install

# Test
composer test
./vendor/bin/phpunit

# Lint
room lint

# Format
room lint --fix

# Type Check
# PHP uses declare(strict_types=1) for type safety
```

## Code Style Guidelines

- **Indentation**: 4 spaces (for PHP files)
- **Naming**: camelCase for methods, PascalCase for classes
- **Imports**: Use fully qualified class names, import at top
- **Comments**: PHPDoc blocks for classes and methods
- **Error Handling**: Custom exceptions extending base Exception class
- **Declarations**: Always use `declare(strict_types=1)` at top of files
- **Visibility**: Explicit visibility modifiers (public/protected/private)

## Project Structure

- `src/`: Main source code with PSR-4 autoloading
- `src/Normalizers/`: Platform-specific normalizers (Twitter, LinkedIn, etc.)
- `src/Exceptions/`: Custom exception classes
- `tests/`: PHPUnit test files
- `Factory.php`: Main entry point for URL parsing
- `Result.php`: Contains parsed URL data and metadata

## Development Notes

- Uses PSR-4 autoloading with `Dealroom\SocialsHelpers\` namespace
- Each platform has dedicated normalizer extending `AbstractNormalizer`
- Normalizers use regex patterns to extract social media handles/IDs
- URL validation includes platform-specific rules (e.g., Twitter handle length)
- Test coverage configured via PHPUnit with coverage reporting

## Additional Instructions

- see @README.md for more information specific to this project
- see @.claude/mothership-instructions.md for global instructions required to be followed
