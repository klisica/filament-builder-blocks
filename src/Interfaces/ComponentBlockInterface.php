<?php

namespace KLisica\FilamentBuilderBlocks\Interfaces;

use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Placeholder;

interface ComponentBlockInterface
{
    // PHP file clas sname.
    public function getClassPath(): string;

    // View refference key.
    public function getView(): string;

    // Readable name for Filament panel.
    public function getName(): string;

    // Get field with inputs for Filament panel.
    public function getFieldset(): Fieldset;

    // Preview URL on Flowbite page.
    public function getPreviewLink(): Placeholder | null;
}

