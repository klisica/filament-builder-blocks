# Filament Builder Blocks

[![Latest Version on Packagist](https://img.shields.io/packagist/v/klisica/filament-builder-blocks.svg?style=flat-square)](https://packagist.org/packages/klisica/filament-builder-blocks)
[![Total Downloads](https://img.shields.io/packagist/dt/klisica/filament-builder-blocks.svg?style=flat-square)](https://packagist.org/packages/klisica/filament-builder-blocks)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/klisica/filament-builder-blocks/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/klisica/filament-builder-blocks/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![GitHub Issues](https://img.shields.io/github/issues/klisica/filament-builder-blocks.svg?style=flat-square)](https://github.com/klisica/filament-builder-blocks/issues)
[![GitHub License](https://img.shields.io/github/license/klisica/filament-builder-blocks.svg?style=flat-square)](https://github.com/klisica/filament-builder-blocks/blob/main/LICENSE)

A Laravel package that extends Filament's default Builder input with custom blocks defined as sections. This package simplifies the creation of CMS features for web pages by storing JSON data in the database and rendering it into final HTML code.

## Installation
``` php
 #TODO
```

You can install the package via composer:

```bash
composer require klisica/filament-builder-blocks
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-builder-blocks-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-builder-blocks-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-builder-blocks-views"
```

## Usage

```php
$filamentBuilderBlocks = new KLisica\FilamentBuilderBlocks();
echo $filamentBuilderBlocks->echoPhrase('Hello, KLisica!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Krševan Lisica](https://github.com/klisica)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
