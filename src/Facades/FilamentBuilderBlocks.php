<?php

namespace KLisica\FilamentBuilderBlocks\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \KLisica\FilamentBuilderBlocks\FilamentBuilderBlocks
 */
class FilamentBuilderBlocks extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \KLisica\FilamentBuilderBlocks\FilamentBuilderBlocks::class;
    }
}
