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

- `renderSections(...)` - Returns a fully formatted HTML code for each section,
- `cleanup(...)` - Cleans unused attributes and values on store and update methods on filaments Create and Edit pages.

<br />

## Installation

1. Require the package via composer:

```bash
composer require klisica/filament-builder-blocks
```

2. Install it to publish the config file:

```bash
php artisan filament-builder-blocks:install
```

3. Open the `config/filament-builder-blocks.php` file and set the `path` value to root destination where you'll have you PHP classes (or leave it as it is).

4. Run make section command to create your first example section class with the blade view file:

```bash
php artisan make:section Hero
```

<br />

## Default folder structure example

Main section `Hero.php` will be displayed in builder dropdown, while child sections `ExampleHero.php` and `AdvancedHero.php` will be displayed as toggle buttons.

```
├── app
│   ├── Sections
│   │   ├── Header
│   │   │   ├── ExampleHero.php
│   │   │   ├── AdvancedHero.php
│   │   │
│   │   ├── Hero.php
```

Creating layouts for each section block component.

```
├── resources
│   ├── views
│   │   ├── sections
│   │   │   ├── example-hero.blade.php
│   │   │   ├── advanced-hero.blade.php
```

> [!NOTE]
> To be sure that on running the `cleanup()` helper your data won't be remove use the **`content.`** prefix on `make` input methods. This is used a handler to avoid storing inputs that you still need to show for descpritive purposes (i.e. Placeholder component). Take the `ExampleHero.php` as example:

```php
class ExampleHero extends AbstractSectionItemProvider
{
    public function getFieldset(): Fieldset
    {
        return Fieldset::make($this->getName())
            ->schema([
                Placeholder::make('contact_links')->columnSpanFull(),  // Will get cleared out.
                TextInput::make('content.heading'),    // Will keep on save methods.
            ]);
    }
}

```

Example for adding cleanup on some Filaments Resource Edit Page:

```php
protected function mutateFormDataBeforeSave(array $data): array
{
    return (new FilamentBuilderBlocks)->cleanup($data);
}
```

<br />

## Rendering components

1. Build sections in controller:

```php
$sections = (new FilamentBuilderBlocks)->renderSections(
    sections: $pages->content,    // Page sections stored in content column
    wrappingSections: $layout->content    // Layout sections stored in content column (includes the `yield` field),
    configs: ['page' => $page, 'layout' => $layout]    // Can be whatever you need to bind in `blade.php` files
);

return view('dynamic')->with('sections', $sections);
```

2. Display them in a `dynamic.blade.php` (or whatever you name it) file:

```php
@foreach($sections as $section)
    {!! $section !!}
@endforeach
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Krševan Lisica](https://github.com/klisica)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
