<?php

namespace KLisica\FilamentBuilderBlocks;

use KLisica\FilamentBuilderBlocks\Components\BuilderBlocksInput;

class FilamentBuilderBlocks
{
    /**
     * Creates a new instance of the BuilderBlocksInput class with the given name and sections,
     * and returns the result of calling the `make`, `sections`, and `columnSpanFull` methods on it.
     *
     * @param string $name The name of the BuilderBlocksInput instance.
     * @param string|null $sections The sections to be passed to the `sections` method. Defaults to null.
     * @return mixed The result of calling the `make`, `sections`, and `columnSpanFull` methods on the BuilderBlocksInput instance.
     */
    public static function make(string $name, ?string $sections = null)
    {
        $builderInput = new BuilderBlocksInput($name);
        return $builderInput->make($name)->sections($sections)->columnSpanFull();
    }
}
