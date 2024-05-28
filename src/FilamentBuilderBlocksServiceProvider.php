<?php

namespace KLisica\FilamentBuilderBlocks;

use KLisica\FilamentBuilderBlocks\Commands\FilamentBuilderBlocksCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentBuilderBlocksServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('filament-builder-blocks')
            ->hasConfigFile()
            ->hasViews()
            ->hasCommand(FilamentBuilderBlocksCommand::class);
    }
}
