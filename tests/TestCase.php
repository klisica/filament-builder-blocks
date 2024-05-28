<?php

namespace KLisica\FilamentBuilderBlocks\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use KLisica\FilamentBuilderBlocks\FilamentBuilderBlocksServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'KLisica\\FilamentBuilderBlocks\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            FilamentBuilderBlocksServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_filament-builder-blocks_table.php.stub';
        $migration->up();
        */
    }
}
