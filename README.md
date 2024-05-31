# Filament Builder Blocks

### 🏗️ A Simpler Web CMS Builder for Laravel Filament

[![Latest Version on Packagist](https://img.shields.io/packagist/v/klisica/filament-builder-blocks.svg?style=flat-square)](https://packagist.org/packages/klisica/filament-builder-blocks)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/klisica/filament-builder-blocks/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/klisica/filament-builder-blocks/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/klisica/filament-builder-blocks.svg?style=flat-square)](https://packagist.org/packages/klisica/filament-builder-blocks)
[![GitHub License](https://img.shields.io/github/license/klisica/filament-builder-blocks.svg?style=flat-square)](https://github.com/klisica/filament-builder-blocks/blob/main/LICENSE)

## Create, manage & customize
By re-using Filament's [Builder Input](https://filamentphp.com/docs/3.x/forms/fields/builder) this package enables you to create **custom section blocks as PHP classes** (i.e. `DefaultHeader.php`) enabling you to use all features supported in Filament.

Each section block uses his own **blade view file** (i.e. `default-header.blade.php`) with support for dynamic data binded in PHP classes.

Another great helper functions that are ready-to-use:

- `renderSections()` - Returns a fully formatted HTML code for each section,
- `cleanup()` - Cleans unused attributes and values on store and update methods on filaments Create and Edit pages.

<br />

## Installation

1. You can require the package via composer:

```bash
composer require klisica/filament-builder-blocks
```

2. And then install it with:

```bash
php artisan filament-builder-blocks:install
```

3. Next, open the `config/filament-builder-blocks.php` file and set the `path` value to root destination where you'll have you PHP classes.


**Folder structure example:**
```
// Creating a root Header.php section, with child section blocks in Header folder.
├── app
│   ├── Sections
│   │   ├── Header
│   │   │   ├── DefaultHeader.php
│   │   │   ├── AcvancedHeader.php
│   │   │
│   │   ├── Header.php

// Creating layouts for each section block component.
├── resources
│   ├── views
│   │   ├── sections
│   │   │   ├── default-header.blade.php
│   │   │   ├── advanced-header.blade.php
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
